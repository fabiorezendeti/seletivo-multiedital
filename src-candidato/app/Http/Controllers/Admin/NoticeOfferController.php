<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Process\Offer;
use App\Models\Process\Notice;
use App\Http\Requests\StoreOffer;
use App\Models\Course\CampusOffer;
use App\Models\Organization\Campus;
use App\Http\Controllers\Controller;
use App\Repository\CampusRepository;
use App\Repository\OfferRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Queue;

class NoticeOfferController extends Controller
{

    private OfferRepository $offerRepository;
    private CampusRepository $campusRepository;

    public function __construct(OfferRepository $offerRepository, CampusRepository $campusRepository)
    {
        $this->offerRepository = $offerRepository;
        $this->campusRepository = $campusRepository;
    }

    public function create(Notice $notice)
    {
        $response = Gate::inspect('offersCreate', Offer::class);
        if ($response->allowed()) {
            $offer = new Offer();
            return $this->makeView($notice, $offer);
        } else {
            return back()->with('error', $response->message());
        };
    }

    private function makeView(Notice $notice, Offer $offer)
    {
        $campus = $this->campusRepository->getCampusesByNoticeWithCourseOffers($notice);
        return view('admin.notices.offers.edit', compact('notice', 'offer', 'campus'));
    }

    public function store(StoreOffer $request, Notice $notice)
    {
        $offer = $this->offerRepository->create($notice, $request->all());
        return redirect()->route('admin.notices.offers.edit', ['notice' => $notice, 'offer' => $offer])
            ->with('success', "A oferta foi cadastrada com sucesso");
    }

    public function edit(Notice $notice, Offer $offer)
    {
        return $this->makeView($notice, $offer);
    }

    public function update(StoreOffer $request, Notice $notice, Offer $offer)
    {
        $this->offerRepository->update($notice, $offer, $request->all());
        return redirect()->route('admin.notices.offers.edit', ['notice' => $notice, 'offer' => $offer])
            ->with('success', "A oferta foi atualizada com sucesso");
    }    

    public function destroy(Notice $notice, Offer $offer)
    {
        try {
            $this->offerRepository->delete($notice, $offer);
            return redirect()->route('admin.notices.show', ['notice' => $notice])
                ->with('success', "A oferta foi excluída com sucesso");
        } catch (QueryException $exception) {
            return redirect()->back()
                ->with('error', "Não foi possível excluir, existem vagas alocadas");
        }
        
    }    

}
