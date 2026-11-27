<?php ($author = $book->author_name ?: ($book->author?->name ?? 'Penulis tidak dicantumkan')); ?>
<a href="<?php echo e(route('catalog.show', $book)); ?>" class="rb-card">
    <div class="rb-cover c<?php echo e($i % 5); ?>"><b><?php echo e($book->title); ?></b></div>
    <div class="rb-card-meta">
        <p class="rb-card-author"><?php echo e($author); ?></p>
        <?php if($book->category): ?><p class="rb-card-cat"><?php echo e($book->category->name); ?></p><?php endif; ?>
        <div class="rb-card-foot">
            <span class="rb-avail <?php echo e($book->available_count ? 'ok' : 'no'); ?>"><?php echo e($book->available_count ? $book->available_count.' tersedia' : 'Sedang dipinjam'); ?></span>
            <?php if($book->ebook_path): ?><span class="rb-tag">E-book</span><?php endif; ?>
        </div>
    </div>
</a>
<?php /**PATH E:\laragon\www\E-Library_bawaslu\resources\views/partials/book-card.blade.php ENDPATH**/ ?>