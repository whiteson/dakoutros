/* eslint-disable */
import $ from "jquery";
import General from './_generalScripts';
import Menu from './_menu';
import ScrollMagic from 'scrollmagic';
const controller = new ScrollMagic.Controller();
import Plyr from 'plyr';
import debounce from 'Lodash';
// import lazyload from 'lazyload';

// console.log(lazyload());


const App = {
	/**
	 * App.init
	 */
	init() {

		// General scripts
		function initGeneral() {
			return new General();
		}

		function initMegaMenu() {
			return new Menu();
		}

		initGeneral();
		initMegaMenu();
	}

};

function singlePostHeight() {

	const $singlePosImage = $('.single-content__image > img');
	if ($singlePosImage.length > 0) {

		const singleMainImage = document.querySelector('.single-content__image > img');
		const singleMainText = document.querySelector('.single-content__content__text');
		if (singleMainImage.offsetHeight > 0) {
			singleMainText.style.setProperty('max-height', singleMainImage.offsetHeight - 250 + 'px')
			console.log(singleMainImage.offsetHeight);
		}
	}
}

function detectScroll() {
	var lastScrollTop = 0;
	$(window).scroll(function (event) {
		var st = $(this).scrollTop();
		if (st > lastScrollTop) {
			// downscroll code
			return -1;
		} else {
			// upscroll code
			return 1;
		}
		lastScrollTop = st;
	});
}

function imageAnimations() {

	let $imageAnimation = $('[data-image-animation]');
	if ($imageAnimation.length > 0) {
		$imageAnimation.each((i, item) => {
			const scene = new ScrollMagic.Scene({
					triggerElement: item,
					offset: 0,
					reverse: true,
					triggerHook: 0.3,
				})
			
				.on('enter', () => {
					$(item).addClass('is-visible');
					if(detectScroll() < 0){
					}
					else{
						$(item).prev().removeClass('is-visible');
						console.log('scroll down ? ? ?');
					}
				})
				.on('leave', () => {
					$(item).removeClass('is-visible');
					if(detectScroll() < 0){
						// console.log('scroll down on leave? ? ?');
					}
					else{
						// console.log('scroll up on leave ? ? ?');
						$(item).prev().addClass('is-visible');
					}

				})
				.addTo(controller);
			$(window).on('resize', () => {
				controller.updateScene(scene, true);
			});

		});
	}

}

function animateonClick() {
	let $clickelem = $('.single-gallery__image__caption.is-visible');
	$imageAnimation.each((i, item) => {
		elem.on('click', () => {
			this.addClass('clickanimation');
		});

	});
}


function mainContentAnimations() {

	let $contentAnimation = $('[data-content-animation]');

	if ($contentAnimation.length > 0) {
		console.log('content found');
		if (window.innerWidth > 600) {

			$contentAnimation.each((i, item) => {
				const scene = new ScrollMagic.Scene({
						triggerElement: item,
						offset: 0,
						triggerHook: 0.85,
					})
					.on('enter', () => {
						$(item).addClass('is-visible');
					})
					.addTo(controller);
				$(window).on('resize', () => {
					controller.updateScene(scene, true);
				});
			});
		} else {
			$contentAnimation.addClass('is-visible');
		}
	}
}

function playSoundInit(myAudio) {
	let $soundoff = $('.sound__off');
	$soundoff.on('click', () => {
		myAudio.play();
		let $soundon = $('.sound__on');
		$soundoff.hide();
		$soundon.show();

	});
}

function closeSoundInit(myAudio) {
	let $soundon = $('.sound__on');
	$soundon.on('click', () => {
		myAudio.pause();
		let $soundoff = $('.sound__off');
		$soundon.hide();
		$soundoff.show();
	});
}

function initPlyr() {
	try {
		const players = Array.from(document.querySelectorAll('.js-player')).map(p => new Plyr(p));
	} catch (error) {
		console.log(error);
	}
}

function alterSinglePostLanguage(){
	const $languageEn = $('.en');
	const $languageGr = $('.gr');
	const $greekcontent = $('.single-content__content__text--gr');
	const $englishcontent = $('.single-content__content__text--en');

	const $titleen = $('.titleen');
	const $titlegr = $('.titlegr');
	if($languageEn.length > 0){
		$languageEn.on('click', () => {
			//Hide the greek cotnent and show english
			$languageEn.addClass('isactive');
			$languageGr.removeClass('isactive');
			$greekcontent.hide();
			$titlegr.hide();
			$titleen.show();
			$englishcontent.show();
		});
	}
	if($languageGr.length > 0){
		$languageGr.on('click', () => {
			//Hide the greek cotnent and show english
			$languageGr.addClass('isactive');
			$languageEn.removeClass('isactive');

			$englishcontent.hide();
			$greekcontent.show();
			$titlegr.show();
			$titleen.hide();
		});
	}
}


document.addEventListener('DOMContentLoaded', () => {
	App.init();
	//initPlyr(); // Init the videos


// 	let soundUrl = $('.myaudio').data('src');
// 	let myAudio = new Audio(soundUrl);
// 	myAudio.loop = true;
// 
// 	singlePostHeight();
// 	imageAnimations();
// 	playSoundInit(myAudio);
// 	closeSoundInit(myAudio);
// 	alterSinglePostLanguage();


	window.addEventListener('resize', function (event) {
		// singlePostHeight();
	}, true);

});


$(document).on('ready', () => {
	// let $audioUrl = $('.myaudio').data('src');
	// let myAudio = new Audio($audioUrl);

});


window.addEventListener('load', (event) => {
	// mainContentAnimations();
});