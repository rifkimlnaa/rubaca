<?php
namespace Database\Seeders;
use App\Models\{Author,Book,BookCopy,Category,LibrarySetting,Publisher,Shelf,User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class DatabaseSeeder extends Seeder {
 public function run(): void {
  // Default categories and circulation rules are available in all environments.
  $categories=[]; foreach(['Sastra & Fiksi','Sejarah','Sains & Teknologi','Desain & Arsitektur','Pengembangan Diri','Ekonomi'] as $name) $categories[] = Category::firstOrCreate(['slug'=>Str::slug($name)],['name'=>$name]);
  foreach(['loan_days'=>14,'renewal_days'=>7,'fine_per_day'=>1000,'max_active_loans'=>3] as $key=>$value) LibrarySetting::put($key,$value);

  // Demo credentials and sample collection must never be seeded in production.
  if (! app()->environment(['local', 'testing'])) {
   return;
  }
  User::firstOrCreate(['email'=>'admin@ruangbaca.test'],['name'=>'Administrator','password'=>Hash::make('Admin12345!'),'role'=>'admin','is_active'=>true]);
  User::firstOrCreate(['email'=>'petugas@ruangbaca.test'],['name'=>'Nadia Petugas','password'=>Hash::make('Petugas123!'),'role'=>'staff','is_active'=>true]);
  User::firstOrCreate(['email'=>'anggota@ruangbaca.test'],['name'=>'Alya Pratama','password'=>Hash::make('Anggota123!'),'role'=>'member','member_code'=>'MBR-DEMO001','is_active'=>true]);
  $authors=[]; foreach(['Dee Lestari','Andrea Hirata','Yuval Noah Harari','Leila S. Chudori','James Clear','Pidi Baiq'] as $name) $authors[]=Author::firstOrCreate(['name'=>$name]);
  $publishers=[]; foreach(['Bentang Pustaka','Kepustakaan Populer Gramedia','Mizan','Erlangga'] as $name) $publishers[]=Publisher::firstOrCreate(['name'=>$name]);
  $shelf=Shelf::firstOrCreate(['name'=>'Rak A-01'],['location'=>'Lantai 1 · Sayap Barat','description'=>'Fiksi dan sastra kontemporer']);
  $samples=[['Aroma Karsa','Sebuah perjalanan tentang ingatan, indera, dan pencarian.'],['Laskar Pelangi','Kisah persahabatan dan pendidikan dari Belitung.'],['Sapiens','Ringkasan perjalanan panjang umat manusia.'],['Laut Bercerita','Novel tentang kehilangan, ingatan, dan keberanian.'],['Atomic Habits','Panduan membangun kebiasaan kecil yang konsisten.'],['Dilan 1990','Kisah remaja dan romansa di Bandung.']];
  foreach($samples as $i=>$sample){ $b=Book::firstOrCreate(['slug'=>Str::slug($sample[0])],['category_id'=>$categories[$i%count($categories)]->id,'author_id'=>$authors[$i]->id,'publisher_id'=>$publishers[$i%count($publishers)]->id,'shelf_id'=>$shelf->id,'title'=>$sample[0],'description'=>$sample[1],'published_year'=>2018+$i,'is_featured'=>$i<4]); for($j=1;$j<=2;$j++) BookCopy::firstOrCreate(['barcode'=>'RB-'.$b->id.'-'.str_pad((string)$j,4,'0',STR_PAD_LEFT)],['book_id'=>$b->id,'status'=>'available']); }
 }
}