class Init 
	constructor: ->
        @button = new Click($('#cart'))
        .buttonCart()

        new Click($('#button'))
        .buttonAfficheChangePassword()

class Click
    constructor: (@selector) ->

    buttonCart: ->
        $(@selector).on "click", ->
            cart = new Cart($('#cart'))
            cart.ajax()
            return false

    buttonAfficheChangePassword: ->
        @selector.on "click", 'a', ->
            new ChangePassword($(@))
            .ajax()
            return false

    buttonChangePassword: ->
        @selector.on "submit", ->
            new ChangePassword($(@))
            .ajaxForm()
            return false

class ChangePassword
    constructor: (@button) ->

    ajaxForm: ->
        console.log(@button.attr 'action')

        $.ajax
            url: 'compte/change-mot-de-passe'
            success: (html) ->
                console.log(html)

    ajax: ->
        $.ajax
            url:@button.attr 'href'
            success: (html) ->
                options = $('#options')
                options
                .fadeOut
                    complete: ->
                        options
                        .empty()
                        .append($(html))
                        new Click($('#changePassword'))
                        .buttonChangePassword()
                .fadeIn()

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