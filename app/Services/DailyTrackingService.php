<?php

namespace App\Services;

use App\Models\Ayah;
use App\Models\DailyTracking;
use App\Models\DailyTrackingDetail;
use App\Models\Surah;
use Exception;
use Illuminate\Support\Facades\DB;

class DailyTrackingService
{
    public function validateSurahsLimits(array $trackingData): void
    {
        foreach ($trackingData as $data) {
            $isPresent = (isset($data['is_present']) && (int) $data['is_present'] === 1);

            if ($isPresent && ! empty($data['surahs'])) {
                foreach ($data['surahs'] as $surahData) {
                    $fromSurahId = $surahData['from_surah_id'] ?? null;
                    $toSurahId = $surahData['to_surah_id'] ?? null;
                    $fromAyah = isset($surahData['from_ayah']) ? (int) $surahData['from_ayah'] : null;
                    $toAyah = isset($surahData['to_ayah']) ? (int) $surahData['to_ayah'] : null;

                    if (empty($fromSurahId) && empty($fromAyah)) {
                        continue;
                    }

                    if (! empty($fromSurahId) && ! empty($fromAyah) && ! empty($toAyah)) {
                        $targetToSurahId = ! empty($toSurahId) ? $toSurahId : $fromSurahId;

                        $fromSurah = Surah::find($fromSurahId);
                        $targetSurah = Surah::find($targetToSurahId);

                        if ($fromSurah && $targetSurah) {
                            $maxAyahFrom = Ayah::where('surah_id', $fromSurah->id)->max('number_in_surah') ?: 999;
                            $maxAyahTo = Ayah::where('surah_id', $targetSurah->id)->max('number_in_surah') ?: 999;

                            if ($fromAyah > $maxAyahFrom) {
                                throw new Exception("رقم آية البداية ({$fromAyah}) يتجاوز عدد آيات سورة {$fromSurah->name}");
                            }
                            if ($toAyah > $maxAyahTo) {
                                throw new Exception("رقم آية النهاية ({$toAyah}) يتجاوز عدد آيات سورة {$targetSurah->name}");
                            }
                        }
                    }
                }
            }
        }
    }

    public function storeBatch(string $trackingDate, array $trackingInputs): void
    {
        DB::transaction(function () use ($trackingDate, $trackingInputs) {
            foreach ($trackingInputs as $data) {
                $studentId = $data['student_id'];
                $isPresent = (isset($data['is_present']) && (int) $data['is_present'] === 1);

                $totalHifzPages = 0.00;
                $totalMurajaPages = 0.00;

                $tracking = DailyTracking::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'date' => $trackingDate,
                    ],
                    [
                        'is_present' => $data['is_present'] ?? 1,
                        'rating' => $isPresent ? ($data['rating'] ?? null) : null,
                        'notes' => $data['notes'] ?? null,
                    ]
                );

                $tracking->details()->delete();

                if ($isPresent && ! empty($data['surahs'])) {
                    foreach ($data['surahs'] as $surahData) {
                        $fromSurahId = $surahData['from_surah_id'] ?? null;
                        $toSurahId = $surahData['to_surah_id'] ?? null;
                        $fromAyah = $surahData['from_ayah'] ?? null;
                        $toAyah = $surahData['to_ayah'] ?? null;

                        if (empty($fromSurahId) || empty($fromAyah)) {
                            continue;
                        }

                        $targetToSurahId = ! empty($toSurahId) ? $toSurahId : $fromSurahId;
                        if (empty($toAyah)) {
                            $toAyah = $fromAyah;
                        }

                        $pages = $this->calculatePages($fromSurahId, $fromAyah, $targetToSurahId, $toAyah);

                        if (($surahData['type'] ?? '') === 'hifz') {
                            $totalHifzPages += $pages;
                        } elseif (($surahData['type'] ?? '') === 'muraja') {
                            $totalMurajaPages += $pages;
                        }

                        DailyTrackingDetail::create([
                            'daily_tracking_id' => $tracking->id,
                            'type' => $surahData['type'] ?? 'hifz',
                            'surah_id' => $fromSurahId,
                            'to_surah_id' => $targetToSurahId,
                            'from_ayah' => $fromAyah,
                            'to_ayah' => $toAyah,
                        ]);
                    }
                }

                $tracking->update([
                    'hifz_pages' => $totalHifzPages,
                    'muraja_pages' => $totalMurajaPages,
                ]);
            }
        });
    }

    public function updateSingle(DailyTracking $tracking, array $data): void
    {
        DB::transaction(function () use ($tracking, $data) {
            $isPresent = (int) $data['is_present'] === 1;
            $totalHifzPages = 0.00;
            $totalMurajaPages = 0.00;

            $tracking->update([
                'date' => $data['date'],
                'is_present' => $data['is_present'],
                'rating' => $isPresent ? ($data['rating'] ?? null) : null,
                'notes' => $data['notes'] ?? null,
            ]);

            $tracking->details()->delete();

            if ($isPresent && ! empty($data['surahs'])) {
                foreach ($data['surahs'] as $surahData) {
                    $fromSurahId = $surahData['from_surah_id'] ?? null;
                    $toSurahId = $surahData['to_surah_id'] ?? null;
                    $fromAyah = $surahData['from_ayah'] ?? null;
                    $toAyah = $surahData['to_ayah'] ?? null;

                    if (! empty($fromSurahId) && ! empty($fromAyah)) {
                        if (! isset($surahData['type']) || ! in_array($surahData['type'], ['hifz', 'muraja'])) {
                            continue;
                        }

                        if (empty($toAyah)) {
                            $toAyah = $fromAyah;
                        }

                        $targetToSurahId = ! empty($toSurahId) ? $toSurahId : $fromSurahId;
                        $pages = $this->calculatePages($fromSurahId, $fromAyah, $targetToSurahId, $toAyah);

                        if ($surahData['type'] === 'hifz') {
                            $totalHifzPages += $pages;
                        } elseif ($surahData['type'] === 'muraja') {
                            $totalMurajaPages += $pages;
                        }

                        DailyTrackingDetail::create([
                            'daily_tracking_id' => $tracking->id,
                            'type' => $surahData['type'],
                            'surah_id' => $fromSurahId,
                            'to_surah_id' => $targetToSurahId,
                            'from_ayah' => $fromAyah,
                            'to_ayah' => $toAyah,
                        ]);
                    }
                }
            }

            $tracking->update([
                'hifz_pages' => $totalHifzPages,
                'muraja_pages' => $totalMurajaPages,
            ]);
        });
    }

    public function calculatePages($fromSurahId, $fromAyah, $toSurahId, $toAyah): float
    {
        if (! $fromSurahId || ! $fromAyah || ! $toSurahId || ! $toAyah) {
            return 0.00;
        }

        $fromSurah = Surah::find($fromSurahId);
        $toSurah = Surah::find($toSurahId);

        if (! $fromSurah || ! $toSurah) {
            return 0.00;
        }

        $validAyahs = Ayah::where(function ($query) use ($fromSurah, $toSurah, $fromAyah, $toAyah) {
            if ($fromSurah->id === $toSurah->id) {
                $query->where('surah_id', $fromSurah->id)
                    ->whereBetween('number_in_surah', [min($fromAyah, $toAyah), max($fromAyah, $toAyah)]);

                return;
            }

            $minNum = min($fromSurah->number, $toSurah->number);
            $maxNum = max($fromSurah->number, $toSurah->number);

            $startSurah = $fromSurah->number <= $toSurah->number ? $fromSurah : $toSurah;
            $endSurah = $fromSurah->number <= $toSurah->number ? $toSurah : $fromSurah;

            $startAyahNum = ($startSurah->id === $fromSurah->id) ? $fromAyah : $toAyah;
            $endAyahNum = ($endSurah->id === $toSurah->id) ? $toAyah : $fromAyah;

            $query->where(function ($q) use ($startSurah, $startAyahNum) {
                $q->where('surah_id', $startSurah->id)
                    ->where('number_in_surah', '>=', $startAyahNum);
            })->orWhere(function ($q) use ($endSurah, $endAyahNum) {
                $q->where('surah_id', $endSurah->id)
                    ->where('number_in_surah', '<=', $endAyahNum);
            })->orWhereIn('surah_id', function ($sub) use ($minNum, $maxNum) {
                $sub->select('id')
                    ->from('surahs')
                    ->whereBetween('number', [$minNum + 1, $maxNum - 1]);
            });
        })->get();

        if ($validAyahs->isEmpty()) {
            return 0.00;
        }

        $totalPages = 0.0;
        $pagesGrouped = $validAyahs->groupBy('page');

        foreach ($pagesGrouped as $pageNumber => $pageAyahs) {
            $totalAyahsInPage = Ayah::where('page', $pageNumber)->count();
            if ($totalAyahsInPage > 0) {
                $totalPages += ($pageAyahs->count() / $totalAyahsInPage);
            }
        }

        return (float) number_format($totalPages, 2, '.', '');
    }

    public function attachLastHifzPosition($students): void
    {
        if ($students->isEmpty()) {
            return;
        }

        $studentIds = $students->pluck('id');

        $latestDetails = DailyTrackingDetail::where('type', 'hifz')
            ->whereHas('dailyTracking', function ($q) use ($studentIds) {
                $q->whereIn('student_id', $studentIds);
            })
            ->whereIn('id', function ($query) use ($studentIds) {
                $query->select(\DB::raw('MAX(daily_tracking_details.id)'))
                    ->from('daily_tracking_details')
                    ->join('daily_tracking', 'daily_tracking_details.daily_tracking_id', '=', 'daily_tracking.id')
                    ->whereIn('daily_tracking.student_id', $studentIds)
                    ->where('daily_tracking_details.type', 'hifz')
                    ->groupBy('daily_tracking.student_id');
            })
            ->with('dailyTracking')
            ->get()
            ->keyBy(fn($detail) => $detail->dailyTracking->student_id);

        $surahIds = $latestDetails->map(function ($detail) {
            return $detail->to_surah_id ?? $detail->surah_id;
        })->filter()->unique();

        $surahs = Surah::whereIn('id', $surahIds)->pluck('name', 'id');

        foreach ($students as $student) {
            $lastDetail = $latestDetails->get($student->id);

            if ($lastDetail) {
                $surahId = $lastDetail->to_surah_id ?? $lastDetail->surah_id;
                $ayah = $lastDetail->to_ayah ?? $lastDetail->from_ayah;

                $student->last_surah_id = $surahId;
                $student->last_ayah = $ayah;
                $student->last_surah_name = $surahs->get($surahId);
            } else {
                $student->last_surah_id = null;
                $student->last_ayah = null;
                $student->last_surah_name = null;
            }
        }
    }
}
