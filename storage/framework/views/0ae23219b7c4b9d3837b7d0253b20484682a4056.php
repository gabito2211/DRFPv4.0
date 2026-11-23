<footer>
    <div class="footer-info">
        &copy; <?php echo date('Y'); ?> - <?php if(Auth::check() && Auth::user()->sede): ?>
                    <?php echo e(Auth::user()->sede->biblioteca); ?>

                <?php else: ?>
                    Sede Principal <br><small style="font-size: 0.75rem; color: var(--text-muted);">(Administración)</small>
                <?php endif; ?>
    </div>
</footer>