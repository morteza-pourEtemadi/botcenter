function getW(name) {
    name = name.substr(name.length - 1);
    switch (name) {
        case '1':
        case '2':
            return 1;
        case '3':
            return 2;
        case '4':
        case '6':
            return 3;
    }
    return 4;
}

function getH(name) {
    name = name.substr(name.length - 1);
    switch (name) {
        case '2':
        case '6':
            return 3;
    }
    return 2;
}

function setSliderNav() {
    var obj = $('.sliderNav ul li');
    var h = (100 / obj.size()) + '%';

    obj.css({
        'height' : h,
        'font-size' : '2vh'
    });
    var fObj = $('.sliderNav ul li:first');
    fObj.click();
    fObj.addClass("navActive");

    var hObj = $('.hSliderNav ul li');
    var w = (100 / hObj.size()) + '%';

    hObj.css({
        'width' : w,
        'font-size' : '2vh'
    });
    var fhObj = $('.hSliderNav ul li:first');
    fhObj.click();
    fhObj.addClass("navActive");
}

var ok;
var i, j, k;
$(document).ready(function() {
    for (k = 0; k <= n; k++) {
        var id = '#' + k;
        ok = false;
        var w = getW($(id).attr('class'));
        var h = getH($(id).attr('class'));
        for (i = 0; i < lines; i++) {
            for (j = 0; j < 4; j++) {
                if (sortedArr[i][j] != 0) {
                    continue;
                }
                if ([(j+w-1)] in sortedArr[i] && [i+h-1] in sortedArr && [j] in sortedArr[i+h-1]) {
                    if (sortedArr[i][j+w-1] == 0 && sortedArr[i+h-1][j] == 0 && sortedArr[i+h-1][j+w-1] == 0) {
                        var l = (j * 25) + 'vw';
                        var t = (i * 25) + 'vh';

                        $(id).css({
                            top: t,
                            left: l
                        });
                        var ii, jj;
                        for (ii = i; ii <= i+h-1; ii++) {
                            for (jj = j; jj <= j+w-1; jj++) {
                                sortedArr[ii][jj] = 1;
                            }
                        }
                        ok = true;
                    }
                }
                if (ok) break;
            }
            if (ok) break;
        }
    }
    $('.justColor').each(function (i, obj) {
        $(obj).css({
            'background-color' : $(obj).attr('about'),
            'color' : '<?=getColor()?>'
        });
        if ($(obj).find('.description').text() == '') {
            $(obj).find('.title').css({
                'margin' : '45%'
            });
        }
    });

    setSliderNav();

    $('.afterContent').css({
        'top' : (lines * 25) + 'vh'
    })
});

$(document).resize(function () {
    setSliderNav();
});

$('.slc').click(function () {
    var target = $('img[id="' + $(this).attr('about') + '"]');
    if (target.length == false) {
        target = $('div[id="' + $(this).attr('about') + '"]');
    }
    var targetText = $('div[about="' + $(this).attr('about') + '"]');
    var stx = target.attr('id').split('-');
    var cid = stx[0];
    $('.sliderPic[id^="'+cid+'"]').animate({
        'opacity' : '0'
    }, 50, 'linear', function () {
        $(this).css({
            'display' : 'none'
        });
        $('.imageContent[about^="' + cid + '"]').css({
            'display' : 'none'
        });
        target.css({
            'display' : 'block'
        }).animate({
            'opacity' : '0.9'
        }, 180);
        targetText.css({
            'display' : 'block'
        }).animate({
            'opacity' : '1'
        }, 90)
    });

    $('.slc[about^="'+cid+'"]').removeClass("navActive");
    $(this).addClass("navActive");
});

//#to-top button appears after scrolling
var fixed = false;
$(document).scroll(function() {
    if ($(this).scrollTop() > 250) {
        if (!fixed) {
            fixed = true;
            $('#to-top').show("slow", function() {
                $('#to-top').css({
                    'position' : 'fixed',
                    'display' : 'block'
                });
            });
        }
    } else {
        if (fixed) {
            fixed = false;
            $('#to-top').hide("slow", function() {
                $('#to-top').css({
                    display: 'none'
                });
            });
        }
    }
});

// Scrolls to the selected menu item on the page
$("a[href*='#']:not([href='#'],[data-toggle],[data-target],[data-slide])").click(function() {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
            $('html,body').animate({

                scrollTop: target.offset().top
            }, 1000);
            return false;
        }
    }
});