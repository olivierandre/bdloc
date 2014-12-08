class Init 
	constructor: ->
        @button = new Click($('#cart'))
        .buttonCart()

        new Click($('#button'))
        .buttonAfficheChangePassword()

        new Click($('#updateAccountUser'))
        .buttonUpdateProfil()

        new Click($('#formAbonnement input'))
        .focusAbonnement()

        new Click($('#formAbonnement'))
        .submitAbonnement()

class Click
    constructor: (@selector) ->

    buttonCart: ->
        $(@selector).on "click", ->
            cart = new Cart($('#cart'))
            cart.ajax()
            return false

    buttonAfficheChangePassword: ->
        @selector.on "click", '#managePassword', ->
            new ChangePassword($(@))
            .ajax()
            return false

    buttonUpdateProfil: ->
        @selector.on 'submit', ->
            new ManageProfil($(@))
            .ajaxUpdateProfil()
            return false

    buttonChangePassword: ->
        @selector.on "submit", ->
            new ChangePassword($(@))
            .ajaxFormChangePassword()
            return false

    buttonAfficheOptions: ->
        @selector.on "click", 'a', ->
            new ManageProfil($(@))
            .ajaxAfficheOptions()
            return false;

    focusAbonnement: ->
        @selector.on "focus", ->
            new Abonnement($(@).attr 'value')
            .focusAbo()

    submitAbonnement: ->
        @selector.on "submit", ->
            new Abonnement($(@))
            .payer()
            return false

class Abonnement
    constructor: (@value) ->

    focusAbo: ->
        span = $('#montantAbonnement').find('span')
        if @value is "mensuel"
            span.text("L’abonnement mensuel coûte le prix d’une seule BD : 12€ TTC")
        else
            span.text("Un abonnement annuel permet d’économiser 2 mois : 120€ TTC")

    payer: ->
        checked = @value.find 'input[type=radio]'
            .is ':checked'

        if not checked
            $('.alert-danger')
            .effect "shake"
        else
            


class ManageProfil
    constructor: (@button) ->

    ajaxUpdateProfil: ->
        $.ajax
            url: @button.attr 'action'
            type: @button.attr 'method'
            data: @button.serialize()
            success: (html) ->
                html = $(html)
                detailsAccount = $('#detailsAccount')
                newDetailsAccount = html.find('#detailsAccount').children()
                
                detailsAccount
                .fadeOut
                    complete: ->
                        detailsAccount
                        .empty()
                        .append(newDetailsAccount)
                        new Click($('#updateAccountUser'))
                        .buttonUpdateProfil()
                .fadeIn()

    ajaxAfficheOptions: ->
        $.ajax
            url: @button.attr 'href'
            success: (html) ->
                console.log(html)
                html = $(html)
                options = $('#options')
                newOptions = html.filter('#otherOptions')

                options.fadeOut
                    complete: ->
                        options
                        .empty()
                        .append(newOptions)
                        new Click($('#button'))
                        .buttonAfficheChangePassword()
                .fadeIn()


class ChangePassword
    constructor: (@button) ->

    ajaxFormChangePassword: ->
        $.ajax
            url: @button.attr 'action'
            type: @button.attr 'method'
            data: @button.serialize()
            success: (html) ->
                html = $(html)
                div = $('#formChangePassword')
                newDiv = html.find('#change')
                newForm = html.find('#changePassword')
                new Click(newForm)
                .buttonChangePassword()

                div.fadeOut
                    complete: ->
                        div
                        .empty()
                        div
                        .append(newDiv) 
                        new Click($('#backOptions'))
                        .buttonAfficheOptions() 
                .fadeIn()


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
                        new Click($('#backOptions'))
                        .buttonAfficheOptions()
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