<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
 public function up(): void {
  Schema::table('books', function(Blueprint $table) {
   $table->string('book_code',24)->nullable()->unique()->after('id');
   $table->string('author_name',180)->nullable()->after('author_id');
   $table->string('publisher_name',180)->nullable()->after('publisher_id');
   $table->string('shelf_label',120)->nullable()->after('shelf_id');
  });
  DB::table('books')->orderBy('id')->each(function($book) {
   DB::table('books')->where('id',$book->id)->update([
    'book_code'=>'RB-'.str_pad((string)$book->id,7,'0',STR_PAD_LEFT),
    'author_name'=>$book->author_id ? DB::table('authors')->where('id',$book->author_id)->value('name') : null,
    'publisher_name'=>$book->publisher_id ? DB::table('publishers')->where('id',$book->publisher_id)->value('name') : null,
    'shelf_label'=>$book->shelf_id ? DB::table('shelves')->where('id',$book->shelf_id)->value('name') : null,
   ]);
  });
 }
 public function down(): void {
  Schema::table('books', function(Blueprint $table) {
   $table->dropUnique(['book_code']); $table->dropColumn(['book_code','author_name','publisher_name','shelf_label']);
  });
 }
};
