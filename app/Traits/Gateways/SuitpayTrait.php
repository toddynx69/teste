<?php namespace App\Traits\Gateways;use App\Models\AffiliateHistory;use App\Models\Deposit;use App\Models\Gateway;use App\Models\SuitPayPayment;use App\Models\Transaction;use App\Models\User;use App\Models\Wallet;use App\Notifications\NewDepositNotification;use App\Notifications\NewWithdrawalNotification;use App\Traits\Affiliates\AffiliateHistoryTrait;use Carbon\Carbon;use GuzzleHttp\Client;use http\Client\Request;use Illuminate\Support\Facades\Http;use Illuminate\Support\Facades\Validator;trait SuitpayTrait{use AffiliateHistoryTrait;protected static string $sg_0;protected static string $kn_1;protected static string $va_2;private static function generateCredentials(){$cy_3=Gateway::first();if(!empty($cy_3)){self::$sg_0=$cy_3->$ok_4;self::$kn_1=$cy_3->$ar_5;self::$va_2=$cy_3->$ps_6;}}public static function requestQrcode($vi_7){$cy_3=\Helper::getSetting();$pf_8=[base64_decode('YW1vdW50')=>[base64_decode('cmVxdWlyZWQ='),base64_decode('bWF4Og==').$cy_3->$lr_9,base64_decode('bWF4Og==').$cy_3->$nh_10],base64_decode('Y3Bm')=>[base64_decode('cmVxdWlyZWQ='),base64_decode('bWF4OjI1NQ==')],];$ic_11=Validator::make($vi_7->all(),$pf_8);if($ic_11->fails()){return[base64_decode('c3RhdHVz')=>false,base64_decode('ZXJyb3Jz')=>$ic_11->errors()];}self::generateCredentials();$vl_12=Http::withHeaders([base64_decode('Y2k=')=>self::$kn_1,base64_decode('Y3M=')=>self::$va_2])->post(self::$sg_0.base64_decode('Z2F0ZXdheS9yZXF1ZXN0LXFyY29kZQ=='),[base64_decode('cmVxdWVzdE51bWJlcg==')=>time(),base64_decode('ZHVlRGF0ZQ==')=>Carbon::now()->addDay(),base64_decode('YW1vdW50')=>\Helper::amountPrepare($vi_7->$fk_13),base64_decode('c2hpcHBpbmdBbW91bnQ=')=>0.0,base64_decode('dXNlcm5hbWVDaGVja291dA==')=>base64_decode('Y2hlY2tvdXQ='),base64_decode('Y2FsbGJhY2tVcmw=')=>url(base64_decode('L3N1aXRwYXkvY2FsbGJhY2s=')),base64_decode('Y2xpZW50')=>[base64_decode('bmFtZQ==')=>auth()->user()->$fr_14,base64_decode('ZG9jdW1lbnQ=')=>\Helper::soNumero($vi_7->$ch_15),base64_decode('cGhvbmVOdW1iZXI=')=>\Helper::soNumero(auth()->user()->$yh_16),base64_decode('ZW1haWw=')=>auth()->user()->$ud_17]]);if($vl_12->successful()){$nw_18=$vl_12->json();self::generateTransaction($nw_18[base64_decode('aWRUcmFuc2FjdGlvbg==')],\Helper::amountPrepare($vi_7->$fk_13));self::generateDeposit($nw_18[base64_decode('aWRUcmFuc2FjdGlvbg==')],\Helper::amountPrepare($vi_7->$fk_13));return[base64_decode('c3RhdHVz')=>true,base64_decode('aWRUcmFuc2FjdGlvbg==')=>$nw_18[base64_decode('aWRUcmFuc2FjdGlvbg==')],base64_decode('cXJjb2Rl')=>$nw_18[base64_decode('cGF5bWVudENvZGU=')]];}return[base64_decode('c3RhdHVz')=>false,];}public static function consultStatusTransaction($vi_7){self::generateCredentials();$vl_12=Http::withHeaders([base64_decode('Y2k=')=>self::$kn_1,base64_decode('Y3M=')=>self::$va_2])->post(self::$sg_0.base64_decode('Z2F0ZXdheS9jb25zdWx0LXN0YXR1cy10cmFuc2FjdGlvbg=='),[base64_decode('dHlwZVRyYW5zYWN0aW9u')=>base64_decode('UElY'),base64_decode('aWRUcmFuc2FjdGlvbg==')=>$vi_7->$br_19,]);if($vl_12->successful()){$nw_18=$vl_12->json();if($nw_18==base64_decode('UEFJRF9PVVQ=')||$nw_18==base64_decode('UEFZTUVOVF9BQ0NFUFQ=')){if(self::finalizePayment($vi_7->$br_19)){return response()->json([base64_decode('c3RhdHVz')=>base64_decode('UEFJRA==')]);}return response()->json([base64_decode('c3RhdHVz')=>$nw_18],400);}return response()->json([base64_decode('c3RhdHVz')=>$nw_18],400);}}public static function finalizePayment($gs_20):bool{$qm_21=Transaction::where(base64_decode('cGF5bWVudF9pZA=='),$gs_20)->where(base64_decode('c3RhdHVz'),0)->first();$cy_3=\Helper::getSetting();if(!empty($qm_21)){$cr_22=User::find($qm_21->$sm_23);if(!empty($cr_22)&&!empty($cr_22->$yz_24)){$ir_25=User::find($cr_22->$yz_24);if(!empty($ir_25)){}}$ag_26=Wallet::where(base64_decode('dXNlcl9pZA=='),$qm_21->$sm_23)->first();if(!empty($ag_26)){$xa_27=Transaction::where(base64_decode('dXNlcl9pZA=='),$qm_21->$sm_23)->count();if($xa_27<=1){$lc_28=\Helper::porcentagem_xn($cy_3->$rt_29,$qm_21->$pm_30);$ag_26->increment(base64_decode('YmFsYW5jZV9ib251cw=='),$lc_28);}if($ag_26->increment(base64_decode('YmFsYW5jZQ=='),$qm_21->$pm_30)){$dk_31=Deposit::where(base64_decode('cGF5bWVudF9pZA=='),$gs_20)->first();if(!empty($dk_31)){$dk_31->update([base64_decode('c3RhdHVz')=>1]);}if($qm_21->update([base64_decode('c3RhdHVz')=>1])){self::updateAffiliate($qm_21->$jm_32,$qm_21->$sm_23,$qm_21->$pm_30);return false;}return false;}return false;}return false;}return false;}private static function generateDeposit($gs_20,$ar_33){Deposit::create([base64_decode('cGF5bWVudF9pZA==')=>$gs_20,base64_decode('dXNlcl9pZA==')=>auth()->user()->$dy_34,base64_decode('YW1vdW50')=>$ar_33,base64_decode('dHlwZQ==')=>base64_decode('UGl4'),base64_decode('c3RhdHVz')=>0]);}private static function generateTransaction($gs_20,$ar_33){$cy_3=\Helper::getSetting();Transaction::create([base64_decode('cGF5bWVudF9pZA==')=>$gs_20,base64_decode('dXNlcl9pZA==')=>auth()->user()->$dy_34,base64_decode('cGF5bWVudF9tZXRob2Q=')=>base64_decode('cGl4'),base64_decode('cHJpY2U=')=>$ar_33,base64_decode('Y3VycmVuY3k=')=>$cy_3->$ny_35,base64_decode('c3RhdHVz')=>0]);}public static function pixCashOut(array$or_36):bool{self::generateCredentials();$vl_12=Http::withHeaders([base64_decode('Y2k=')=>self::$kn_1,base64_decode('Y3M=')=>self::$va_2])->post(self::$sg_0.base64_decode('Z2F0ZXdheS9waXgtcGF5bWVudA=='),[base64_decode('a2V5')=>$or_36[base64_decode('cGl4X2tleQ==')],base64_decode('dHlwZUtleQ==')=>$or_36[base64_decode('cGl4X3R5cGU=')],base64_decode('dmFsdWU=')=>$or_36[base64_decode('YW1vdW50')],base64_decode('Y2FsbGJhY2tVcmw=')=>url(base64_decode('L3N1aXRwYXkvcGF5bWVudA==')),]);if($vl_12->successful()){$nw_18=$vl_12->json();if($nw_18[base64_decode('cmVzcG9uc2U=')]==base64_decode('T0s=')){$uu_37=SuitPayPayment::lockForUpdate()->find($or_36[base64_decode('cGF5bWVudF9pZA==')]);if(!empty($uu_37)){if($uu_37->update([base64_decode('c3RhdHVz')=>1,base64_decode('cGF5bWVudF9pZA==')=>$nw_18[base64_decode('aWRUcmFuc2FjdGlvbg==')]])){return true;}return false;}return false;}return false;}return false;}}