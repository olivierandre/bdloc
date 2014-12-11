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

        new Click($('#showCard'))
        .buttonShowCard()

        new Click($('#formFind'))
        .submitFind()

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
            return false

    buttonShowCard: ->
        @selector.on 'click', ->
            new Abonnement($(@))
            .showInfoCard()
            return false

    focusAbonnement: ->
        @selector.on "focus", ->
            new Abonnement($(@).attr 'value')
            .focusAbo()

    submitAbonnement: ->
        @selector.on "submit", ->
            new Abonnement($(@))
            .afficheCard()
            return false

    submitPaymentSubcription: ->
        @selector.on "submit", ->
            new Abonnement($(@))
            .paySubscription()
            return false

    submitFind: ->
        @selector.on 'submit', ->
            action = $(@).attr 'action'
            tab = $(@).serializeArray()
            title = 'bdloc_appbundle_book[title]'
            illustrator = 'bdloc_appbundle_book[illustrator]'
            action += '/1'
            $(tab).each (index) ->
                name = $(@).attr 'name'
                value = $(@).attr 'value'
                if name is title
                    action += '/' + value
                else if name is illustrator
                    action += '/' + value
            $(@).attr 'action', action
            console.log $(@).attr 'action'

class ManageAjax
    constructor: () ->
        @.start()
        @.end()

    start: () ->
        $(document).ajaxStart ->
            $('#main')
                .hide
                    effect      : 'slide'
                    direction   : 'left'
                    easing      : 'easeInExpo'
                    duration    : 800
                    complete    : ->
                        divMessage = $('<div>')
                        .attr 'class', 'messageAttente'
                        .css
                            'float'             : 'left'
                        .append(
                            $('<h1>')
                            .attr 'class', 'patienceScale'
                            .text 'patience'
                        )

                        divWait = $('<div>')
                        .addClass 'wait'
                        .css
                            'marginLeft'        : '40px'
                            'float'             : 'left'

                        $('<div>')
                        .attr 'class', 'turn'
                        .css
                            'borderRadius'      : '50%'
                            'width'             : '80px'
                            'height'            : '80px'
                            'border'            : '5px solid #679403'
                            'position'          : 'absolute'
                            'borderBottomColor' : 'transparent'
                        .appendTo divWait

                        $('<div>')
                        .attr 'class', 'turnReverse'
                        .css
                            'border-radius'     : '50%'
                            'width'             : '40px'
                            'height'            : '40px'
                            'border'            : '5px solid #679403'
                            'borderTopColor'    : 'transparent'
                            'position'          : 'relative'
                            'left'              : '20px'
                            'top'               : '20px'
                        .appendTo divWait

                        $('<div>')
                        .attr 'id', 'patience'
                        .addClass 'col-xs-offset-2 col-md-offset-4 col-md-8'
                        .css
                            'position'  : 'absolute'
                            'top'       : '50%'
                            'left'      : '0%'
                            'width'     : '100%'
                        .append(divMessage)
                        .append(divWait)
                        .appendTo('body')
                        .show
                            effect      : 'slide'
                            direction   : 'left'
                            easing      : 'easeInExpo' 
                            duration    : 800

    end: () ->
        $(document).ajaxComplete -> 
            $('#patience')
            .hide
                effect      : 'slide'
                direction   : 'left'
                easing      : 'easeInExpo' 
                duration    : 800
                complete    : ->
                    $('#main')
                    .show
                        effect      : 'slide'
                        direction   : 'right'
                        easing      : 'easeInExpo'
                        duration    : 800
                        complete    : ->
                            $('#patience')
                            .remove()


class Abonnement
    constructor: (@value) ->

    focusAbo: ->
        span = $('#montantAbonnement').find('span')
        if @value is "mensuel"
            span.text("L’abonnement mensuel coûte le prix d’une seule BD : 12€ TTC")
        else
            span.text("Un abonnement annuel permet d’économiser 2 mois : 120€ TTC")
        @.afficheSubmit()
        @.abonnementInHiddenInput(@value)

    paySubscription: ->
        new ManageAjax();
        $.ajax
            url: @value.attr 'action'
            type: @value.attr 'method'
            data: @value.serialize()
            success: (html) ->
                html = $ html
                newMain = html.filter '#main'
                console.log newMain.html()
                $('#main')
                .empty()
                .append(newMain)

                console.log($('#main').html())

    afficheSubmit: ->
        form = $('#formAbonnement')
        len = form.find ':submit' 
        .length
        if len is 0
            afficheCard = $('<button>')
            .attr
                'type' : 'submit'
                'class' : 'btn btn-success'
            .text 'étape suivante'
            .appendTo '#formAbonnement'
            new Click(form).
            submitAbonnement()

    showInfoCard: ->
        $.ajax
            url : @value.attr 'href'
            success: (html) ->
                html = $ html
                div = $ '.afficheCarte'

                div
                .append html
                .animate
                    opacity: 1
        $(@value)
        .fadeOut()

    afficheCard: ->
        $.ajax
            url : @value.attr 'action'
            success: (html) ->
                html = $ html
                div = $ '<div>'
                hfour = $('<h4>').text 'Vos coordonnées bancaires'

                div.attr
                    'class' : 'col-xs-12 col-md-6'
                    'id' : 'bancaires'

                html.find '#bdloc_appbundle_creditcard_abonnement'
                .val($('#formAbonnement input')
                .val())

                div.append hfour
                .append html
                .appendTo $('#choixAbonnement')

                new Click($('#formPayment'))
                .submitPaymentSubcription()
    
    abonnementInHiddenInput: (@value) ->
        $('#bancaires').find '#bdloc_appbundle_creditcard_abonnement'
        .val(@value)


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
                    effect: 'slide'
                    direction: 'left'
                    easing: 'easeInExpo',
                    duration: 800
                    complete: ->
                        $('#cart')
                        .remove()
                        addRemove
                        .prepend newButton
                .show
                    effect : "slide"
                    direction :'right'
                    easing: 'easeInExpo'
                    duration: 800

$ -> 
    init = new Init()