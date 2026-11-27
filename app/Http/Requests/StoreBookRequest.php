<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreBookRequest extends FormRequest {
 public function authorize(): bool { return $this->user() && in_array($this->user()->role,['admin','staff'],true); }
 public function rules(): array { return ['title'=>['required','string','max:180'],'category_id'=>['required','exists:categories,id'],'author_name'=>['nullable','string','max:180'],'publisher_name'=>['nullable','string','max:180'],'shelf_label'=>['nullable','string','max:120'],'shelf_id'=>['nullable','exists:shelves,id'],'isbn'=>['nullable','string','max:30'],'published_year'=>['nullable','integer','min:1400','max:'.(date('Y')+1)],'description'=>['nullable','string','max:5000'],'is_featured'=>['sometimes','boolean'],'copies'=>['nullable','integer','min:0','max:500'],'cover'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:3072']]; }
}
