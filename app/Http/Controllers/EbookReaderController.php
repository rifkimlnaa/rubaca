<?php
namespace App\Http\Controllers;
use App\Models\{Book,ReadingProgress};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
class EbookReaderController extends Controller {
 public function show(Request $request,Book $book) {
  $this->authorize('readEbook',$book); $progress=ReadingProgress::where('user_id',$request->user()->id)->where('book_id',$book->id)->value('page_number') ?? 1;
  return view('reader.show',compact('book','progress'));
 }
 public function file(Request $request,Book $book): StreamedResponse|\Symfony\Component\HttpFoundation\Response {
  $this->authorize('readEbook',$book); abort_unless($book->ebook_path && Storage::disk('private')->exists($book->ebook_path),404);
  $path=Storage::disk('private')->path($book->ebook_path); $size=filesize($path); $start=0; $end=$size-1; $status=200; $range=$request->header('Range');
  if($range && preg_match('/bytes=(\d*)-(\d*)/',$range,$m)) {
   if($m[1]==='' && $m[2]==='') return response('',416);
   if($m[1]==='') { $suffix=(int)$m[2]; $start=max(0,$size-$suffix); } else $start=(int)$m[1];
   if(isset($m[2]) && $m[2]!=='' && $m[1]!=='') $end=min((int)$m[2],$size-1);
   if($start>$end || $start>=$size) return response('',416,['Content-Range'=>'bytes */'.$size]);
   $status=206;
  }
  $length=$end-$start+1;
  $headers=['Content-Type'=>'application/pdf','Content-Length'=>(string)$length,'Accept-Ranges'=>'bytes','Content-Disposition'=>'inline; filename="reader-'.$book->id.'.pdf"','Cache-Control'=>'private, no-store, no-cache, must-revalidate','Pragma'=>'no-cache','X-Content-Type-Options'=>'nosniff','Cross-Origin-Resource-Policy'=>'same-origin'];
  if($status===206) $headers['Content-Range']="bytes {$start}-{$end}/{$size}";
  return response()->stream(function() use($path,$start,$length) { $handle=fopen($path,'rb'); fseek($handle,$start); $remaining=$length; while($remaining>0 && !feof($handle)) { $chunk=fread($handle,min(8192,$remaining)); if($chunk===false || $chunk==='') break; echo $chunk; $remaining-=strlen($chunk); if(ob_get_level()) ob_flush(); flush(); } fclose($handle); },$status,$headers);
 }
 public function progress(Request $request,Book $book) {
  $this->authorize('readEbook',$book); $data=$request->validate(['page_number'=>['required','integer','min:1','max:100000']]);
  ReadingProgress::updateOrCreate(['user_id'=>$request->user()->id,'book_id'=>$book->id],['page_number'=>$data['page_number'],'updated_at'=>now()]); return response()->json(['saved'=>true]);
 }
}