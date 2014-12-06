class Init 
	constructor: ->
        @button = new Click($('#cart'))
        .buttonCart()

class Click
    constructor: (@selector) ->

    buttonCart: ->
        $(@selector).on "click", ->
            cart = new Cart($('#cart'))
            cart.ajax()
            return false
            
class Cart 
    constructor: (@button) ->

    ajax: ->
        $.ajax
            url:@button.attr 'href'
            success: (html) ->
                addRemove = $('#addRemove')
                panier = '#panier'
                html = $(html)
                cart = html.find panier
                .text()
                $(panier).text cart
                newButton = html.find '#cart'
                new Click(newButton)
                .buttonCart()
                addRemove.hide
                    effect: 'slide',
                    direction: 'left',
                    easing: 'easeInExpo', 
                    duration: 800
                    complete: ->
                        $('#cart')
                        .remove()
                        addRemove
                        .prepend newButton
                .show
                    effect : "slide",
                    direction :'right',
                    easing: 'easeInExpo',
                    duration: 800

$ -> 
    init = new Init()