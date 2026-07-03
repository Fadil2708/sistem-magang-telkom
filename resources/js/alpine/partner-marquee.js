export default (Alpine) => {
    Alpine.data('partnerMarquee', (speed) => ({
        init() {
            const track = this.$el;
            const container = track.parentElement;
            const origItems = Array.from(track.children);
            if (origItems.length === 0) return;
            const measure = () => {
                const last = origItems[origItems.length - 1];
                return last.offsetLeft + last.offsetWidth;
            };

            let anim = null;
            let origW = measure();

            const ensure = () => {
                if (origW === 0) return;
                const cw = container.offsetWidth;
                const sets = Math.ceil(track.children.length / origItems.length);
                const need = Math.max(2, Math.ceil((cw * 2) / origW));
                for (let i = sets; i < need; i++)
                    origItems.forEach(el => track.appendChild(el.cloneNode(true)));
            };

            const start = () => {
                if (anim) anim.cancel();
                origW = measure();
                if (origW === 0) return;
                anim = track.animate([
                    { transform: 'translateX(0)' },
                    { transform: `translateX(-${origW}px)` },
                ], { duration: speed * 1000, iterations: Infinity });
            };

            ensure();
            start();

            let t;
            addEventListener('resize', () => {
                clearTimeout(t);
                t = setTimeout(() => { measure(); ensure(); start(); }, 150);
            });

            this._c = () => { if (anim) anim.cancel(); };
        },
        destroy() { if (this._c) this._c(); }
    }));
};
