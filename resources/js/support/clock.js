
export default function clock() {
    return {
        time: '',
        date: '',
        start() {
            this.update();
            setInterval(() => this.update(), 1000);
        },
        update() {
            const now = new Date();

            this.date = now.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            this.time = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }
    }
}
