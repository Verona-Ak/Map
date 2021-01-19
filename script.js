window.addEventListener('DOMContentLoaded', ()=> {
    'use strict';

        
    const title = document.getElementsByClassName('info__title')[0],
        svgElem = document.querySelector('svg'),
        regions = document.querySelectorAll('.jqvmap-region');

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
                        title.textContent = `${i.name} - страна`;
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
                        document.body.appendChild(prompt);
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
                        document.body.removeChild(prompt);
                        // if(prompt) {
                        //     console.log('prompt');
                        //     prompt.remove();
                        //     prompt = null;
                        // }

                    } 
                }
            }
        })



    });

    function clearProperty(arr, property) {
        for(let i = 0; i < arr.length; i++) {
            if(property == 'fill') {
                arr[i].style = '';
                // arr[i].style.fill = '';
                // arr[i].style.strokeOpacity = '';
                // arr[i].style.stroke = '';
            } else if(property == 'opacity') {
                arr[i].style.opacity = '';
            }
        }


    }

});