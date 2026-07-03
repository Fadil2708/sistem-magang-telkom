export default (Alpine) => {
    Alpine.data('toastStack', (initialToasts = []) => ({
        toasts: [],
        init() {
            initialToasts.forEach(t => {
                const id = Date.now() + Math.random();
                this.toasts.push({ ...t, id });
            });
        },
        add(event) {
            const id = Date.now() + Math.random();
            this.toasts.push({
                id,
                message: event.detail.message,
                type: event.detail.type ?? 'success'
            });
            setTimeout(() => {
                this.toasts = this.toasts.filter(t => t.id !== id);
            }, 4000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }));
};
