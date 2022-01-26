<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Repositories\HR\QrCodeRepository;
use QrCodeGenerator;
use Illuminate\Http\Request;

class QrcodeController extends Controller
{
    private $qrCodeRepository;
    public function __construct(QrCodeRepository $qrCodeRepository) {
        $this->qrCodeRepository = $qrCodeRepository;
    }

    public function index() {

        return view('HR.qrcode.index');

    }

    public function getQrCode(Request $request) {

        $page = $request->page ? $request->page : 1;
        $count = $request->count ? $request->count : 10;
        $isUsed = $request->isUsed ? $request->isUsed : 0;
        $search = $request->search && $request->search != '' && $request->search !== 'null' ? $request->search : null;

        $params = [
            'page' => $page,
            'count' => $count,
            'search' => $search,
            'isUsed' => $isUsed
        ];

        $code = $this->qrCodeRepository->getQrCode(json_decode(json_encode($params)));

        return response()->json($code, 200);

    }

    public function storeQrcode() {

        $qr = $this->qrCodeRepository->storeQRcode();

        return response()->json($qr, 200);

    }

    public function generateQR($id) {

        $qr = $this->qrCodeRepository->generate($id);

        $qrcode = QrCodeGenerator::size(200)->generate($qr);

        return view('HR.qrcode.qr', compact(['qrcode', 'qr']));
        ;

    }

    public function verified(Request $request) {

        $qrcode = $request->qrcode ? $request->qrcode : null ;

        $params = [
            'name' => $qrcode
        ];

        $code = $this->qrCodeRepository->verified(json_decode(json_encode($params)));

        return response()->json($code, 200);

    }
}
