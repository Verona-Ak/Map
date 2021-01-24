window.addEventListener('DOMContentLoaded', ()=> {
    'use strict';

        
    const title = document.getElementsByClassName('info__title')[0],
        paragraph = document.getElementsByClassName('info__paragraph')[0],
        svgElem = document.querySelector('svg'),
        regions = document.querySelectorAll('.jqvmap-region'),
        headerBtn = document.getElementsByClassName('header__btn')[0],
        field = document.getElementsByClassName('header__field')[0],
        main = document.getElementsByClassName('main')[0];

    let prompt = document.createElement('div');
    

    let request = new XMLHttpRequest();
    request.open('GET', 'data.json', true);
    request.setRequestHeader('Content-type', 'appLication/json; charset=utf-8');
    request.send();
    request.addEventListener('load', () => {

        let data = JSON.parse(request.response);
        svgElem.addEventListener('click', (e)=> {
            clearProperty(regions, 'fill');
            if(e.target) {
                for(let i of data) {
                    if(e.target.id === i.id) {
                        $('.info__paragraph').slideUp(700);
                        paragraph.textContent = '';
                        title.textContent = `${i.name}`;

                        let requestPost = new XMLHttpRequest();
                        let body = 'link=' + encodeURIComponent(i.link);
                        requestPost.open('POST', 'parser.php', true);
                        requestPost.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=utf-8');
                        requestPost.send(body);
                        requestPost.addEventListener('load', ()=> {
                            paragraph.textContent = requestPost.response;
                            $('.info__paragraph').slideDown(700);
                        });
                        e.target.style.cssText = `fill: #6e3d3d; stroke-opacity: 0.7; stroke: #ffffff;`;
                    } 
                }
            }
        });

        svgElem.addEventListener('mouseover', (e)=> {

            if(e.target) {
                for(let i of data) {
                    if(e.target.id === i.id) {
                        e.target.style.opacity = '0.25';
                        e.target.title = i.name;
                        let coords = e.target.getBoundingClientRect();
                        // console.log(coords);
                        main.appendChild(prompt);
                        
                        prompt.classList.add('floatprompt');
                        prompt.textContent = i.name;

                        let left = coords.left+(e.target.offsetWidth-prompt.offsetWidth)/2;
                        if(left < 0) {
                            left = 0; // не заезжать за левый край окна
                        }
                        let top = coords.top - prompt.offsetHeight - 5;

                        if (top < 0) { // если подсказка не помещается сверху, то отображать её снизу
                            top = coords.top + e.target.offsetHeight + 5;
                        }

                        prompt.style.left = left + 'px';
                        prompt.style.top = top + 'px';
                    }
                }
            }
        });
        svgElem.addEventListener('mouseout', (e)=> {
            if(e.target) {
                for(let i of data) {
                    if(e.target.id === i.id) {
                        clearProperty(regions, 'opacity');
                        // main.removeChild(prompt);
                        prompt.remove();

                    } 
                }
            }
        })

        headerBtn.addEventListener('click', ()=> {
            clearProperty(regions, 'fill');
            clearProperty(regions, 'opacity');

            for(let obj of data) {
                if(obj.name.toLowerCase() === field.value.trim().toLowerCase()) {
                    $('.info__paragraph').slideUp(700);
                    paragraph.textContent = '';

                    title.textContent = `${obj.name}`;

                    let requestPost = new XMLHttpRequest();
                    let body = 'link=' + encodeURIComponent(obj.link);
                    requestPost.open('POST', 'parser.php', true);
                    requestPost.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=utf-8');
                    requestPost.send(body);
                    requestPost.addEventListener('load', ()=> {
                        paragraph.textContent = requestPost.response;
                        $('.info__paragraph').slideDown(700);
                    });
                    let index = 0;
                    while(index < regions.length) {
                        index++;
                        if(regions[index].id === obj.id) {
                            regions[index].style.cssText = `fill: #6e3d3d; stroke-opacity: 0.7; stroke: #ffffff;`;
                        }
                    }
                    
                }
                
            }

        });


    });

    function clearProperty(arr, property) {
        for(let i = 0; i < arr.length; i++) {
            if(property == 'fill') {
                arr[i].style = '';
            } else if(property == 'opacity') {
                arr[i].style.opacity = '';
            }
        }
    }
});