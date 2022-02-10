var list = document.getElementsByClassName('list-item');

for (let i = 0; i < list.length; i++) {
    const item = list[i];
    item.addEventListener('click', function(e){
        item.getElementsByClassName('moreinfo')[0].classList.toggle('hide');
        item.getElementsByClassName('list-item-actions')[0].classList.toggle('hide');
        item.classList.toggle('small');
    });
}