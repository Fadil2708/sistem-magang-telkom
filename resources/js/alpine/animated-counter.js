export default (Alpine) => {
    Alpine.data('animatedCounter', (target = 0) => ({
        num: 0,
        target: target,
        counting: false,
        init() {
            const obs = new IntersectionObserver(([entry]) => {
                if (entry.isIntersecting && !this.counting) {
                    this.counting = true;
                    obs.unobserve(this.$el);
                    const step = Math.ceil(this.target / 30);
                    const t = setInterval(() => {
                        this.num = Math.min(this.num + step, this.target);
                        if (this.num >= this.target) clearInterval(t);
                    }, 30);
                }
            }, { threshold: 0.3 });
            obs.observe(this.$el);
        },
        get displayNum() {
            return this.num.toLocaleString() + '+';
        }
    }));
};
