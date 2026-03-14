<?php get_header(); ?>

<main class="py-20 bg-bg-light dark:bg-dark-bg grow transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-5">
        <?php if (have_posts()):
            while (have_posts()):
                the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <!-- Header -->
                    <header class="mb-12 text-center">
                        <div class="text-primary font-semibold mb-4 uppercase tracking-wider text-sm">
                            <?php the_category(', '); ?>
                        </div>
                        <h1 class="text-4xl md:text-5xl font-bold text-text-dark dark:text-dark-text mb-6">
                            <?php the_title(); ?>
                        </h1>
                        <div class="text-text-light dark:text-dark-text-muted">
                            <?php echo get_the_date(); ?>
                        </div>
                    </header>

                    <!-- Featured Image -->
                    <?php if (has_post_thumbnail()): ?>
                        <div class="mb-12 rounded-xl overflow-hidden shadow-lg">
                            <?php the_post_thumbnail('large', ['class' => 'w-full h-auto object-cover']); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Content -->
                    <div class="prose prose-lg max-w-none text-text-light dark:text-dark-text-muted dark:prose-invert mb-16">
                        <?php the_content(); ?>
                    </div>

                    <!-- Lightbox Script -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            // Find all images in the content area
                            const contentImages = Array.from(document.querySelectorAll('.prose img'));
                            if (contentImages.length === 0) return;

                            const lightbox = document.getElementById('lightbox');
                            const lightboxImg = document.getElementById('lightbox-img');
                            const counter = document.getElementById('lightbox-counter');
                            let currentIndex = 0;
                            const imageUrls = contentImages.map(img => img.src);

                            // Make images clickable
                            contentImages.forEach((img, index) => {
                                img.style.cursor = 'pointer';
                                img.classList.add('hover:opacity-90', 'transition-opacity');
                                img.addEventListener('click', (e) => {
                                    e.preventDefault(); // Prevent default link behavior if wrapped in <a>
                                    openLightbox(index);
                                });
                            });

                            function openLightbox(index) {
                                currentIndex = index;
                                updateLightbox();
                                lightbox.classList.remove('hidden');
                                setTimeout(() => lightbox.classList.remove('opacity-0'), 10);
                                document.body.style.overflow = 'hidden';
                            }

                            window.closeLightbox = function () {
                                lightbox.classList.add('opacity-0');
                                setTimeout(() => lightbox.classList.add('hidden'), 300);
                                document.body.style.overflow = '';
                            }

                            function updateLightbox() {
                                lightboxImg.src = imageUrls[currentIndex];
                                if (counter) counter.textContent = `${currentIndex + 1} / ${imageUrls.length}`;
                            }

                            window.nextImage = function () {
                                currentIndex = (currentIndex + 1) % imageUrls.length;
                                updateLightbox();
                            }

                            window.prevImage = function () {
                                currentIndex = (currentIndex - 1 + imageUrls.length) % imageUrls.length;
                                updateLightbox();
                            }

                            // Keyboard navigation
                            document.addEventListener('keydown', function (e) {
                                if (lightbox.classList.contains('hidden')) return;
                                if (e.key === 'Escape') closeLightbox();
                                if (e.key === 'ArrowRight') nextImage();
                                if (e.key === 'ArrowLeft') prevImage();
                            });

                            // Close on background click
                            lightbox.addEventListener('click', function (e) {
                                if (e.target === lightbox) closeLightbox();
                            });
                        });
                    </script>

                    <!-- Lightbox Modal -->
                    <div id="lightbox"
                        class="fixed inset-0 z-[100] bg-black/90 hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
                        <button onclick="closeLightbox()"
                            class="absolute top-4 right-4 text-white hover:text-primary transition-colors z-[101]">
                            <i data-lucide="x" class="w-10 h-10"></i>
                        </button>

                        <button onclick="prevImage()"
                            class="absolute left-4 text-white hover:text-primary transition-colors z-[101] hidden md:block">
                            <i data-lucide="chevron-left" class="w-10 h-10"></i>
                        </button>

                        <img id="lightbox-img" src="" alt="Gallery Image"
                            class="max-h-[90vh] max-w-[90vw] object-contain shadow-2xl rounded-sm">

                        <button onclick="nextImage()"
                            class="absolute right-4 text-white hover:text-primary transition-colors z-[101] hidden md:block">
                            <i data-lucide="chevron-right" class="w-10 h-10"></i>
                        </button>

                        <div class="absolute bottom-4 left-0 right-0 text-center text-white/80 text-sm">
                            <span id="lightbox-counter"></span>
                        </div>
                    </div>

                </article>
            <?php endwhile; endif; ?>
    </div>
</main>

<?php get_footer(); ?>