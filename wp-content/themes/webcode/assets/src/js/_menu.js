
class Menu {
	constructor() {
		this.testVariable = 'script working';
		this.init();
		this.closeMenuEsc();
		this.initMenu();
	}

	init() {
		// for tests purposes only
		console.log(this.testVariable);
	}

	initMenu() {
		const $hasChildren = $('.menu__item--has-children > .menu__link');
		
		if ($hasChildren.length > 0) {
			$hasChildren.each((i, item) => {
				$(item).on('click', (e) => {
					e.preventDefault();
					let targetHeight = 0;
					const $parent = $(item).parent();
	
					clearOpenedSubMenu();
					if ($parent.hasClass('active')) {
						$parent.removeClass('active');
						$parent.parent().removeClass('submenu-open');
						$parent.find('.menu__list--submenu').removeClass('open');
						$parent.find('.menu__list--submenu').stop().animate({
							height: 0
						}, 400);
					} else {
						$hasChildren.parent().removeClass('active');
						$hasChildren.parent().find('.menu__list--submenu').removeClass('open');
						$parent.addClass('active');
						$parent.parent().addClass('submenu-open');
						$parent.find('.menu__list--submenu').addClass('open');
	
						const $submenuContentList = $parent.find('.menu__list--submenu .menu__item');
						$submenuContentList.each(function () { // eslint-disable-line
							targetHeight += $(this).outerHeight();
						});
	
						$parent.find('.menu__list--submenu').stop().animate({
							height: targetHeight + 20
						}, 400);
					}
				});
			});
		}
	}


	closeMenuEsc() {
		const $body = $('body');
		const $burgerBtn = $('.site-header__burger');
		const $mainMenu = $('.site-header__menu');
		const $hasChildren = $('.menu__item--has-children');
		const $subMenu = $('.menu__list--submenu');
	
		$body.keyup((e) => {
			if (e.keyCode === 27) {
				// e.preventDefault();
				// e.stopPropagation();
				clearOpenedSubMenu();
				$burgerBtn.removeClass('open');
				$body.removeClass('menu-open');
				$mainMenu.removeClass('open');
				$hasChildren.removeClass('active');
				$subMenu.removeClass('open');
			}
		});
	}

	clearOpenedSubMenu() {
		const $submenuContent = $('.menu--main').find('.menu__list--submenu');
		$submenuContent.each(function () { // eslint-disable-line
			if ($(this).hasClass('open')) {
				$(this).stop().animate({
					height: 0
				}, 400);
				$(this).removeClass('open');
			}
		});
	}


}

export default Menu;
