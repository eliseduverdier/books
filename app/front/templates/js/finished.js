const isFinished = new URL(document.URL).searchParams.get('finished')
console.log(isFinished);

if (isFinished) {
    let divs = [];
    let div;
    for (let i = 0; i < 60; i++) {
        div = document.createElement('div')
        div.classList.add('confetti')
        div.setAttribute('style', `--speed: ${Math.random() * 5 + 2}s; --pos:${Math.random() * window.innerWidth}px;`);
        if (i < 20) div.classList.add('blue')
        else if (i < 40) div.classList.add('green')
        else if (i < 60) div.classList.add('yellow')
        div.classList.add('confetti')
        divs.push(div)
    }
    document.getElementsByTagName('body')[0].prepend(...divs)
}
