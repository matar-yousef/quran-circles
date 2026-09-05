<?php

namespace App\Http\Controllers;

use App\Http\Requests\HalaqaRequest;
use App\Models\Halaqa;
use App\Services\HalaqaService;
use Illuminate\Support\Facades\Auth;

class HalaqaController extends Controller
{
    protected $halaqaService;

    public function __construct(HalaqaService $halaqaService)
    {
        $this->halaqaService = $halaqaService;
    }

    public function create()
    {
        if (Auth::user()->halaqas()->exists()) {
            return redirect()->route('dashboard')->with('error', 'لديك حلقة مسجلة بالفعل.');
        }

        return view('halaqa.create')->with('warning', 'يرجى إنشاء الحلقة الخاصة بك أولاً...');
    }

    public function store(HalaqaRequest $request)
    {
        if (Auth::user()->halaqas()->exists()) {
            return redirect()->route('dashboard')->with('error', 'لا يمكنك إنشاء أكثر من حلقة.');
        }

        $halaqa = Halaqa::query()->create($request->validated());
        $halaqa->users()->attach(Auth::id());

        return redirect()->route('halaqa.show', $halaqa->id)->with('success', 'تم إنشاء الحلقة بنجاح.');
    }

    public function show($id)
    {
        $halaqa = Halaqa::findOrFail($id);

        $this->authorize('view', $halaqa);

        $data = $this->halaqaService->getHalaqaDetailsData($id);

        return view('halaqa.show', $data);
    }

    public function edit($id)
    {
        $halaqa = Halaqa::findOrFail($id);

        $this->authorize('update', $halaqa);

        return view('halaqa.edit', compact('halaqa'));
    }

    public function update(HalaqaRequest $request, $id)
    {
        $halaqa = Halaqa::findOrFail($id);

        $this->authorize('update', $halaqa);

        $halaqa->update($request->validated());

        return redirect()->route('halaqa.show', $halaqa->id)->with('success', 'تم تحديث بيانات الحلقة بنجاح.');
    }

    public function destroy($id)
    {
        $halaqa = Halaqa::findOrFail($id);

        $this->authorize('delete', $halaqa);

        $halaqa->delete();

        return redirect()->route('dashboard')->with('success', 'تم حذف الحلقة بنجاح.');
    }
}
