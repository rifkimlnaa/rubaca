@php($author = $book->author_name ?: ($book->author?->name ?? 'Penulis tidak dicantumkan'))
<a href="{{ route('catalog.show', $book) }}" class="rb-card">
    <div class="rb-cover c{{ $i % 5 }}"><b>{{ $book->title }}</b></div>
    <div class="rb-card-meta">
        <p class="rb-card-author">{{ $author }}</p>
        @if($book->category)<p class="rb-card-cat">{{ $book->category->name }}</p>@endif
        <div class="rb-card-foot">
            <span class="rb-avail {{ $book->available_count ? 'ok' : 'no' }}">{{ $book->available_count ? $book->available_count.' tersedia' : 'Sedang dipinjam' }}</span>
            @if($book->ebook_path)<span class="rb-tag">E-book</span>@endif
        </div>
    </div>
</a>
