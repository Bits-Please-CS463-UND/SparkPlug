function setClock(clock: HTMLSpanElement){
    const now = new Date();
    let hour = (now.getHours() % 12).toString().padStart(2, '0');
    if (now.getHours() == 0)
        hour = '12';
    const minute = now.getMinutes().toString().padStart(2, '0');
    const ampm = now.getHours() >= 12 ? "PM" : "AM"
    clock.innerText = `${hour}:${minute} ${ampm}`
}
window.addEventListener('load', () => {
    const clock = document.getElementById('time');
    if (clock instanceof HTMLSpanElement){
        setClock(clock);
        const recursiveClockSet = () => {
            setClock(clock);
            setTimeout(recursiveClockSet, 60000);
        }

        const msTillNextMinute = 60000 - new Date().getTime() % 60000;
        setTimeout(recursiveClockSet, msTillNextMinute);
    }
})