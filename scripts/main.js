const PadloperHtmx = {
	initHTMXXRequestedWithXMLHttpRequest: function () {
		document.body.addEventListener("htmx:configRequest", (event) => {
			const csrf_token = PadloperHtmx.getCSRFToken()
			event.detail.headers[csrf_token.name] = csrf_token.value
			// add XMLHttpRequest to header to work with $config->ajax
			event.detail.headers["X-Requested-With"] = "XMLHttpRequest"
		})
	},

	listenToHTMXRequests: function () {
		// before send
		htmx.on("htmx:beforeSend", function (event) {
			// console.log(
			// 	"PadloperHtmx - listenToHTMXRequests - beforeSend - event",
			// 	event
			// )
		})

		// after swap
		htmx.on("htmx:afterSwap", function (event) {
			// console.log(
			// 	"PadloperHtmx - listenToHTMXRequests - afterSwap - event",
			// 	event
			// )
		})

		// after settle
		// @note: aftersettle is fired AFTER  afterswap
		// @todo: maybe even use css to transition in so user doesn't 'perceive' a delay?
		htmx.on("htmx:afterSettle", function (event) {
			// ------------
			// if event was adding single product (and not updating cart amount in side cart)
			// we need to refresh side cart without increasing amounts
			const pathInfo = event.detail.pathInfo.path
			if (pathInfo === "/padloper/add/") {
				const triggerElementID = "padloper_add_single_product"
				PadloperHtmx.triggerHTMXReloadSideCart(triggerElementID)
			}
			// ----------------
			// re-init event listeners
			PadloperHtmx.initMonitorCartItemAmountChange()
		})
	},
	getCSRFToken: function () {
		// find hidden input with id 'csrf-token'
		const tokenInput = htmx.find("._post_token")
		return tokenInput
	},

	// check if a htmx request included a  'refresh cart' class
	// if true, will trigger updating the side cart after settle when cart item amount is changed by a previous htmx operation.
	isCartItemReloadRequired: function (event) {
		const triggerElement = event.detail.requestConfig.elt
		return triggerElement.classList.contains("padloper_cart_item_updater")
	},

	initMonitorCartItemAmountChange: function () {
		// add event listener to increase cart item amount buttons
		document
			.querySelectorAll(
				".padloper_cart_item_amount_updater, .padloper_cart_item_amount_remover"
			)
			// add both click and double click event listeners
			.forEach((i) =>
				["click", "dblclick"].forEach(function (event) {
					i.addEventListener(
						event,
						PadloperHtmx.handleCartItemAmountChange,
						false
					)
				})
			)
	},
	handleCartItemAmountChange: function (event) {
		// const cartItemAmountUpdateElement = event.target.parentElement
		// @note: need closest button since click could be on the svg or the path nested in the button
		let cartItemAmountUpdateElement
		const clickedElement = event.target
		if (
			clickedElement.classList.contains("padloper_cart_item_amount_remover")
		) {
			cartItemAmountUpdateElement = clickedElement
		} else {
			cartItemAmountUpdateElement = clickedElement.closest("button")
		}

		PadloperHtmx.setChangedCartItemValues(cartItemAmountUpdateElement)
	},
	setChangedCartItemValues: function (updatedCartItemElement) {
		const currentCartItemIDInputElement = document.getElementById(
			"padloper_cart_update_product_id"
		)
		const currentCartItemQuantityInputElement = document.getElementById(
			"padloper_cart_update_product_quantity"
		)

		if (currentCartItemIDInputElement && currentCartItemQuantityInputElement) {
			const triggerElementID = "padloper_cart_updater"
			// @TODO - MAYBE A BIT VERBOSE CALLING ANOTHER METHOD TO SET VALUES?
			PadloperHtmx.updateCurrentCartItemValues(
				currentCartItemIDInputElement,
				currentCartItemQuantityInputElement,
				updatedCartItemElement
			).then(PadloperHtmx.triggerHTMXReloadSideCart(triggerElementID))
		}
	},

	async updateCurrentCartItemValues(
		currentCartItemIDInputElement,
		currentCartItemQuantityInputElement,
		updatedCartItemElement
	) {
		const cartItemID = updatedCartItemElement.dataset.cartItemId
		const currentCartItemQuantity = parseInt(
			updatedCartItemElement.dataset.cartItemQuantity
		)
		const isDecreased = updatedCartItemElement.dataset.updaterType == "decrease"

		let updatedCartItemQuantity = isDecreased
			? currentCartItemQuantity - 1
			: currentCartItemQuantity + 1
		if (updatedCartItemQuantity < 0) {
			updatedCartItemQuantity = 0
		}
		// -------
		currentCartItemIDInputElement.value = cartItemID
		currentCartItemQuantityInputElement.value = updatedCartItemQuantity
	},
	// Update and refresh side cart
	triggerHTMXReloadSideCart(triggerElementID) {
		// for submitting then fetching edited cart
		const eventName = "padloperfetchupdatedcart"
		const triggerElement = document.getElementById(triggerElementID)
		htmx.trigger(triggerElement, eventName)
	},
}

// ------------------
function jqueryAddToCart() {
	// jQuery example of how to make add to cart buttons ajaxified
	$(".padloper-cart-add-product").submit(function (event) {
		// console.log(event)
		event.preventDefault()
		const $form = $(this)
		const url = $form.attr("action")

		// Send the data using post
		const posting = $.post(url, $form.serialize())

		posting.done(function (data) {
			if (data.errors) {
				let str = ""
				$.each(data.errors, function (i, val) {
					str = str + val
				})
				alert(str)
			} else {
				$("#totalQty").html(data.totalQty)
				$("#numberOfTitles").html(data.numberOfTitles)
				$("#totalAmount").html(data.totalAmount)
			}
		})
	})
}

// ~~~~~~~~~~~~~~~~~~

/**
 * DOM ready
 *
 */
document.addEventListener("DOMContentLoaded", function (event) {
	if (typeof htmx !== "undefined") {
		// console.log("INIT HTMX")
		// init htmx header
		PadloperHtmx.initHTMXXRequestedWithXMLHttpRequest()
		// init listen to htmx requests
		PadloperHtmx.listenToHTMXRequests()
		// init listen to sidecart increase/decrease item amount button
		PadloperHtmx.initMonitorCartItemAmountChange()
	}

	// jqueryAddToCart()
})

// ALPINE
document.addEventListener("alpine:init", () => {
	Alpine.store("Padloper2DemoStore", {
		// PROPERTIES
		//----------------
		// BOOLEANS

		// DATA
		// carousel
		carousel_active_slide: 1,
		carousel_slides: [],
		// side cart
		cartOpen: false,
	})

	Alpine.data("Padloper2DemoData", () => ({
		//---------------
		// FUNCTIONS

		// SIDE CART

		// -------

		// CAROUSEL
		// @TODO: BELOW FLOW OF INIT/SET ETC IS A BIT VERBOSE!
		/**
		 * Init data for carousel/slides.
		 * @return {void}.
		 */
		initCarouselData() {
			this.setAllCarouselData()
		},

		initSideCart() {
			this.setStoreValue("cartOpen", false)
		},

		handleCarouselPreviousSlideNumber() {
			const currentActiveSlideNumber = this.getCarouselActiveSlideNumber()
			const totalNumberOfSlides = this.getTotalNumberOfSlides()
			// -------
			const activeSlideNumber =
				currentActiveSlideNumber === 1
					? totalNumberOfSlides
					: currentActiveSlideNumber - 1
			// -----
			this.setCarouselActiveSlideNumber(activeSlideNumber)
		},
		handleCarouselNextSlideSlideNumber() {
			const currentActiveSlideNumber = this.getCarouselActiveSlideNumber()
			const totalNumberOfSlides = this.getTotalNumberOfSlides()
			// -------
			const activeSlideNumber =
				currentActiveSlideNumber === totalNumberOfSlides
					? 1
					: currentActiveSlideNumber + 1
			// -----
			this.setCarouselActiveSlideNumber(activeSlideNumber)
		},

		// ------
		/**
		 * Set all the carousel data.
		 *
		 * @return {void}.
		 */
		setAllCarouselData() {
			const carouselData = this.getPadloper2DemoCarouselConfig()
			this.setStoreValue("carousel_active_slide", carouselData.activeSlide)
			this.setStoreValue("carousel_slides", carouselData.slides)
		},

		// ##############################

		// ~~~~~~~~~~~~~~~~

		/**
		 * Set a store property value.
		 * @param any value Value to set in store.
		 * @return {void}.
		 */
		setStoreValue(property, value) {
			this.$store.Padloper2DemoStore[property] = value
		},

		setCarouselActiveSlideNumber(id) {
			this.setStoreValue("carousel_active_slide", id)
		},

		setIsCartOpen() {
			const toggleCartOpenStatus = !this.getIsCartOpen()
			this.setStoreValue("cartOpen", toggleCartOpenStatus)
		},
		// ~~~~~~~~~~~~~~~

		/**
		 * Get the the whole Padloper2DemoStore store.
		 * @returns {object}
		 */
		getStore() {
			return this.$store.Padloper2DemoStore
		},

		/**
		 * Get the value of a given store property.
		 * @param string property Property in store whose value to return
		 * @returns {any}
		 */
		getStoreValue(property) {
			return this.$store.Padloper2DemoStore[property]
		},

		/**
		 *Get the ProcessWire config sent for inventory bulk edit and inline-edit view.
		 * @return object.
		 */
		getPadloper2DemoCarouselConfig() {
			return Padloper2Demo
		},

		getCarouselSlides() {
			return this.getStoreValue("carousel_slides")
		},

		getCarouselActiveSlideNumber() {
			return this.getStoreValue("carousel_active_slide")
		},

		getTotalNumberOfSlides() {
			const carouselSlides = this.getCarouselSlides()
			return carouselSlides.length
		},

		isShow(slide_id) {
			const currentActiveSlideNumber = this.getCarouselActiveSlideNumber()
			return currentActiveSlideNumber === slide_id
		},

		getIsCartOpen() {
			return this.getStoreValue("cartOpen")
		},
	}))
})
