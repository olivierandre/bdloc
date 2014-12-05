class Init 
	constructor: ->
        button = new Button()

class Button
    constructor: ->
        this.buttonCart()

    buttonCart: ->
        selector = $("#cart")
        selector.on "click", ->
            label = new Label()
            .getLabel(this)
            cart = new Cart()
            cart.manageCart(selector, label)
            return false
            
class Cart 
    constructor: ->

    manageCart: (selector, label) ->
        labelAdd = "ajouter au panier"
        labelRemove = "retirer du panier"
        cart = $('#panier')
        item = parseInt(cart.text())

        if label.toLowerCase() is labelAdd
            selector.text(labelRemove)
            cart.text(item += 1)
        else
            selector.text(labelAdd)
            cart.text(item -= 1)

    ajax: ->    

class Label
    constructor: ->

    getLabel: (selector) ->
        $(selector).text()

$ -> 
    init = new Init()