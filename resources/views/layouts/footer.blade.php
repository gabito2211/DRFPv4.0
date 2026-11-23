<footer>
    <div class="footer-info">
        &copy; <?php echo date('Y'); ?> - @if(Auth::check() && Auth::user()->sede)
                    {{ Auth::user()->sede->biblioteca }}
                @else
                    Sede Principal <br><small style="font-size: 0.75rem; color: var(--text-muted);">(Administración)</small>
                @endif
    </div>
</footer>