// Padloper 2 Starter Site - DEMO 1

const PadloperDemo1 = {
	initHTMXXRequestedWithXMLHttpRequest: function () {
		document.body.addEventListener("htmx:configRequest", (event) => {
			const csrf_token = PadloperDemo1.getCSRFToken()
			event.detail.headers[csrf_token.name] = csrf_token.value
			// add XMLHttpRequest to header to work with $config->ajax
			event.detail.headers["X-Requested-With"] = "XMLHttpRequest"
		})
	},

	listenToHTMXRequests: function () {
		// before send
		htmx.on("htmx:beforeSend", function (event) {
			// your beforeSend code here
		})

		// after swap
		htmx.on("htmx:afterSwap", function (event) {
			// your afterSwap code here
		})

		// after settle
		// @note: aftersettle is fired AFTER  afterswap
		htmx.on("htmx:afterSettle", function (event) {
			// ------------
			// if event was adding single product (and not updating cart amount in side cart)
			// we need to refresh side cart without increasing amounts
			const pathInfo = event.detail.pathInfo.path
			if (pathInfo === "/padloper/add/") {
				const triggerElementID = "padloper_add_single_product"
				PadloperDemo1.triggerHTMXReloadSideCart(triggerElementID)
			}
			// ----------------
			// re-init event listeners
			PadloperDemo1.initMonitorCartItemAmountChange()
		})
	},
	getCSRFToken: function () {
		// find hidden input with id 'csrf-token'
		const tokenInput = htmx.find("._post_token")
		return tokenInput
	},

	// check if a htmx request included a 'refresh cart' class
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
						PadloperDemo1.handleCartItemAmountChange,
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

		PadloperDemo1.setChangedCartItemValues(cartItemAmountUpdateElement)
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
			PadloperDemo1.updateCurrentCartItemValues(
				currentCartItemIDInputElement,
				currentCartItemQuantityInputElement,
				updatedCartItemElement
			).then(PadloperDemo1.triggerHTMXReloadSideCart(triggerElementID))
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

// ~~~~~~~~~~~~~~~~~~

/**
 * DOM ready
 *
 */
document.addEventListener("DOMContentLoaded", function (event) {
	if (typeof htmx !== "undefined") {
		// we have htmx
		// --------
		// init htmx header
		PadloperDemo1.initHTMXXRequestedWithXMLHttpRequest()
		// init listen to htmx requests
		PadloperDemo1.listenToHTMXRequests()
		// init listen to sidecart increase/decrease item amount button
		PadloperDemo1.initMonitorCartItemAmountChange()
	}
})

// ALPINE
document.addEventListener("alpine:init", () => {
	Alpine.store("Padloper2DemoStore", {
		// PROPERTIES
		//----------------
		// BOOLEANS

		// DATA
		/* carousel */
		carousel_active_slide: 1,
		carousel_slides: [],
		/* side cart */
		cartOpen: false,
		/* product attributes */
		product_attributes: [],
		/* product attributes options */
		attributes_options: [],
		// main product
		main_product: {},
		// all product variants
		product_variants: [],
		// ************
		// will contain attribute_id -> option_id for selected variant options
		// if all keys have values, then a complete variant selection has been made
		selected_attribute_option_pairs: {},
		// ************
		selected_variant: {},
		selected_variant_product_id: 0,
		selected_variant_price_with_currency: null,
	})

	Alpine.data("Padloper2DemoData", () => ({
		//---------------
		// FUNCTIONS

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

		// SIDE CART
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

		// *** VARIANTS ***
		// @TODO DISABLE BUTTON + SHOW MESSAGE ON CLICK IF NO VARIANT SELECTED AND ADD TO CART BUTTON IS CLICKED
		initVariants() {
			// SET MAIN PRODUCT
			this.setMainProduct()
			// SET INITIAL LOAD IMAGE
			this.setInitialActiveProductImage()
			// SET ALL PRODUCT VARIANTS
			this.setProductVariants()
			// SET ALL PRODUCT ATTRIBUTES
			// these are the attributes whose options make us variants
			this.setProductAttributes()
			// SET ALL ATTRIBUTES OPTIONS
			// these are the options that make up variants
			this.setAttributesOptions()
			// INIT SELECTED ATTRIBUTE->OPTION PAIRS
			this.initSelectedAttributeOptionPairs()
		},

		initSelectedAttributeOptionPairs() {
			const selectedAttributeOptionPairs = {}
			// loop through available attributes and init selected option for each as blanks
			const attributes = this.getStoreValue("product_attributes")
			for (const attribute of attributes) {
				selectedAttributeOptionPairs[attribute.id] = null
			}
			this.setStoreValue(
				"selected_attribute_option_pairs",
				selectedAttributeOptionPairs
			)
		},

		/**
		 *Get the Padloper Demo variants info for current product.
		 * @returns object.
		 */
		getProductVariantsConfigs() {
			return PadloperDemoVariants
		},

		getAllProductVariants() {
			return this.getStoreValue("product_variants")
		},

		getMainProduct() {
			return this.getStoreValue("main_product")
		},

		getAllProductAttributes() {
			return this.getStoreValue("product_attributes")
		},

		getAllAttributeOptions() {
			return this.getStoreValue("attributes_options")
		},

		getSelectedAttributeOptionPairs() {
			return this.getStoreValue("selected_attribute_option_pairs")
		},

		getProductVariantImageByID(variant_id) {
			let image
			const allProductVariants = this.getAllProductVariants()
			// get requested image values
			const variant = allProductVariants.find(
				(item) => item.variant_id == variant_id
			)
			if (variant) {
				// destructure variant object to only get image values
				image = this.getImageValues(variant)
			}
			return image
		},

		getSelectedAttributeOptionName(attribute_id) {
			const selectedAttributeOptionPairsValues =
				this.getSelectedAttributeOptionPairs()
			const selectedOptionIDForAttribute =
				selectedAttributeOptionPairsValues[attribute_id]
			let optionName = this.getAttributeOptionNameByID(
				selectedOptionIDForAttribute
			)
			if (!optionName) {
				optionName = this.getNoOptionSelectedText()
			}
			return optionName
		},

		// Destructure object to only get image values
		getImageValues(object) {
			return (({
				image_thumb_url,
				image_thumb_big_url,
				image_full_url,
				image_alt,
			}) => ({
				image_thumb_url,
				image_thumb_big_url,
				image_full_url,
				image_alt,
			}))(object)
		},

		getAttributeOptionNameByID(option_id) {
			const options = this.getAllAttributeOptions()
			return options[option_id]
		},

		getNoOptionSelectedText() {
			const productVariantsConfigs = this.getProductVariantsConfigs()
			return productVariantsConfigs.no_option_selection
		},

		// --------------
		getNoVariantSelectedText() {
			const productVariantsConfigs = this.getProductVariantsConfigs()
			return productVariantsConfigs.no_variant_selection
		},

		getNoVariantSelectedPriceText() {
			const productVariantsConfigs = this.getProductVariantsConfigs()
			return productVariantsConfigs.no_variant_selection_price
		},

		getSelectedVariant() {
			const selectedVariant = this.getStoreValue("selected_variant")
				? this.getStoreValue("selected_variant")
				: {}
			return selectedVariant
		},

		getSelectedProductVariantName() {
			const selectedVariant = this.getSelectedVariant()
			let name = selectedVariant.variant_title
			if (!name) {
				name = this.getNoVariantSelectedText()
			}
			//----
			return name
		},

		getSelectedProductVariantPriceWithCurrency() {
			const selectedVariant = this.getSelectedVariant()
			//----
			return selectedVariant.price
		},

		// @TODO DELETE IF NOT NEEDED
		getSelectedVariantID() {
			const selectedVariant = this.getSelectedVariant()
			//----
			return selectedVariant.variant_id
		},

		getProductVariantBySelectedOptionsIDs() {
			let productVariant
			// --------
			// get all available attributes for this product
			const allProductAttributes = this.getAllProductAttributes()
			// get all variants for this product
			const allProductVariants = this.getAllProductVariants()
			// get the selected options for each available attribute
			const selectedAttributeOptionPairs =
				this.getSelectedAttributeOptionPairs()

			// -------------
			// loop through all product variants
			// we want to match their 'options make-up' to selected attribute options
			for (const variant of allProductVariants) {
				// get the options make-up of this variant
				// these are made up of 'attribute_id' -> 'option_id' pairs
				/** @var object variantAttributeOptionPairs */
				const variantAttributeOptionPairs =
					variant.variant_attribute_option_pairs
				/////////////
				// prepare array to hold matched values for this variant in the loop
				let matched = []
				// loop through available attributes to match to selected options
				for (const attribute of allProductAttributes) {
					// check if selected attribute option matches this variant's attribute option make-up
					const isMatch =
						parseInt(variantAttributeOptionPairs[attribute.id]) ===
						parseInt(selectedAttributeOptionPairs[attribute.id])
							? true
							: false
					matched.push(isMatch)
				}

				// check if the last matched array has all TRUE valuues
				const isAllMatched = !matched.some((item) => item === false)
				// if all values are matched, break and return the matched variant
				if (isAllMatched) {
					productVariant = variant
					break
				}
			}

			// --------------

			return productVariant
		},

		//#######

		setMainProduct() {
			const productVariantsConfigs = this.getProductVariantsConfigs()
			const mainProduct = productVariantsConfigs.main_product
			this.setStoreValue("main_product", mainProduct)
		},

		// on load, for variants, we set the main product image as the active image
		setInitialActiveProductImage() {
			const mainProduct = this.getStoreValue("main_product")
			if (mainProduct.image_thumb_url) {
				// destructure main product object to only get image values
				const activeImage = this.getImageValues(mainProduct)
				// --------
				this.setStoreValue("active_image", activeImage)
			}
		},

		setActiveProductVariantImage(variant_id) {
			const activeImage = this.getProductVariantImageByID(variant_id)
			this.setStoreValue("active_image", activeImage)
		},

		setProductVariants() {
			const productVariantsConfigs = this.getProductVariantsConfigs()
			const allProductVariants = productVariantsConfigs.all_variants
			this.setStoreValue("product_variants", allProductVariants)
		},

		setProductAttributes() {
			const productVariantsConfigs = this.getProductVariantsConfigs()
			const productAttributes = productVariantsConfigs.attributes
			this.setStoreValue("product_attributes", productAttributes)
		},

		setAttributesOptions() {
			const productVariantsConfigs = this.getProductVariantsConfigs()
			const attributesOptions = productVariantsConfigs.options
			this.setStoreValue("attributes_options", attributesOptions)
		},

		// @TODO DELETE IF NOT IN USE
		setProductImages() {
			const productVariantsConfigs = this.getProductVariantsConfigs()
			const productImages = productVariantsConfigs.images
			this.setStoreValue("product_images", productImages)
		},

		setSelectedOptionForAttribute(option_id, attribute_id) {
			// ---------
			// SET SELECTION OPTION TO ITS ATTRIBUTE
			// first, get the selectedAttributeOptionPairs
			const selectedAttributeOptionPairs = {
				...this.getSelectedAttributeOptionPairs(),
			}
			// set selected attribute->option pair
			selectedAttributeOptionPairs[attribute_id] = option_id
			// set back to store
			this.setStoreValue(
				"selected_attribute_option_pairs",
				selectedAttributeOptionPairs
			)

			// -----------
			// CHECK IF NO 'NULLS' in variant attribute->option pairs
			// i.e., every option selected/set
			const isVariantSelected = this.isVariantSelected()

			// --------------
			// IF VARIANT SELECTED, SET IT
			if (isVariantSelected) {
				this.setSelectedVariantValues()
			}
		},

		// =======================
		setSelectedVariantValues() {
			const selectedVariant = this.getProductVariantBySelectedOptionsIDs()
			// -------
			// SET SELECTED VARIANT
			this.setStoreValue("selected_variant", selectedVariant)
			// SET SELECTED VARIANT IMAGE
			this.setActiveProductVariantImage(selectedVariant.variant_id)
			//  SET SELECTED VARIANT ID
			this.setStoreValue(
				"selected_variant_product_id",
				selectedVariant.variant_id
			)
			//  SET SELECTED VARIANT PRICE
			this.setStoreValue(
				"selected_variant_price_with_currency",
				selectedVariant.price
			)
		},

		isVariantSelected() {
			const selectedAttributeOptionPairs =
				this.getSelectedAttributeOptionPairs()
			const selectedAttributeOptionPairsValues = Object.values(
				selectedAttributeOptionPairs
			)
			const someNull = selectedAttributeOptionPairsValues.some(
				(item) => item === null
			)
			return !someNull
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
