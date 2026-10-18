{{-- Formulaire partagé entre "ajouter" et "modifier" (@include) --}}
{{-- enctype obligatoire pour envoyer un fichier (image) --}}
<form method="POST" action="{{ $action }}" enctype="multipart/form-data"
      class="bg-white rounded-2xl border border-stone-200 p-6 space-y-4 max-w-2xl"
      x-data="{ preview: @js($product->image_url) }">
    @csrf
    @if ($product->exists) @method('PUT') @endif

    @if ($errors->any())
        <div class="bg-red-50 text-red-700 rounded-xl p-3 text-sm">
            @foreach ($errors->all() as $error) <p>{{ $error }}</p> @endforeach
        </div>
    @endif

    {{-- Arabe (obligatoire) + Français (optionnel : si vide, l'arabe s'affiche) --}}
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-bold mb-1">اسم المنتوج (عربي)</label>
            <input name="name" value="{{ old('name', $product->name) }}" required class="w-full border border-stone-300 rounded-xl px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-bold mb-1">Nom (français)</label>
            <input name="name_fr" value="{{ old('name_fr', $product->name_fr) }}" dir="ltr" class="w-full border border-stone-300 rounded-xl px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-bold mb-1">الوصف (عربي)</label>
            <textarea name="description" rows="4" class="w-full border border-stone-300 rounded-xl px-3 py-2">{{ old('description', $product->description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-bold mb-1">Description (français)</label>
            <textarea name="description_fr" rows="4" dir="ltr" class="w-full border border-stone-300 rounded-xl px-3 py-2">{{ old('description_fr', $product->description_fr) }}</textarea>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-bold mb-1">السعر (دج)</label>
            <input name="price" type="number" min="0" value="{{ old('price', $product->price) }}" required class="w-full border border-stone-300 rounded-xl px-3 py-2">
        </div>
        <div>
            <label class="block text-sm font-bold mb-1">المخزون</label>
            <input name="stock" type="number" min="0" value="{{ old('stock', $product->stock ?? 0) }}" required class="w-full border border-stone-300 rounded-xl px-3 py-2">
        </div>
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">الصورة (2 Mo max)</label>
        <div class="flex items-center gap-4">
            <template x-if="preview"><img :src="preview" class="w-24 h-24 rounded-xl object-cover border"></template>
            {{-- Aperçu immédiat de l'image choisie, avant l'envoi (Alpine) --}}
            <input type="file" name="image" accept="image/*"
                   @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : preview"
                   class="text-sm file:ml-3 file:rounded-lg file:border-0 file:bg-stone-100 file:px-3 file:py-2 file:font-bold">
        </div>
    </div>

    <label class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))>
        <span class="font-bold">ظاهر في البوتيك</span>
    </label>

    <div class="flex gap-3 pt-2">
        <button class="bg-brand hover:bg-brand-dark text-white font-bold px-6 py-2 rounded-xl">حفظ</button>
        <a href="{{ route('admin.products.index') }}" class="px-6 py-2 rounded-xl bg-stone-100 font-bold">إلغاء</a>
    </div>
</form>
