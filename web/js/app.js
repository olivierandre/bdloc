// Generated by CoffeeScript 1.8.0
var Abonnement, Cart, ChangePassword, Click, Init, ManageAjax, ManageProfil;

Init = (function() {
  function Init() {
    this.button = new Click($('#cart')).buttonCart();
    new Click($('#button')).buttonAfficheChangePassword();
    new Click($('#updateAccountUser')).buttonUpdateProfil();
    new Click($('#formAbonnement input')).focusAbonnement();
    new Click($('#showCard')).buttonShowCard();
    new Click($('#formFind')).submitFind();
  }

  return Init;

})();

Click = (function() {
  function Click(selector) {
    this.selector = selector;
  }

  Click.prototype.buttonCart = function() {
    return $(this.selector).on("click", function() {
      var cart;
      cart = new Cart($('#cart'));
      cart.ajax();
      return false;
    });
  };

  Click.prototype.buttonAfficheChangePassword = function() {
    return this.selector.on("click", '#managePassword', function() {
      new ChangePassword($(this)).ajax();
      return false;
    });
  };

  Click.prototype.buttonUpdateProfil = function() {
    return this.selector.on('submit', function() {
      new ManageProfil($(this)).ajaxUpdateProfil();
      return false;
    });
  };

  Click.prototype.buttonChangePassword = function() {
    return this.selector.on("submit", function() {
      new ChangePassword($(this)).ajaxFormChangePassword();
      return false;
    });
  };

  Click.prototype.buttonAfficheOptions = function() {
    return this.selector.on("click", 'a', function() {
      new ManageProfil($(this)).ajaxAfficheOptions();
      return false;
    });
  };

  Click.prototype.buttonShowCard = function() {
    return this.selector.on('click', function() {
      new Abonnement($(this)).showInfoCard();
      return false;
    });
  };

  Click.prototype.focusAbonnement = function() {
    return this.selector.on("focus", function() {
      return new Abonnement($(this).attr('value')).focusAbo();
    });
  };

  Click.prototype.submitAbonnement = function() {
    return this.selector.on("submit", function() {
      new Abonnement($(this)).afficheCard();
      return false;
    });
  };

  Click.prototype.submitPaymentSubcription = function() {
    return this.selector.on("submit", function() {
      new Abonnement($(this)).paySubscription();
      return false;
    });
  };

  Click.prototype.submitFind = function() {
    return this.selector.on('submit', function() {
      var action, illustrator, len, link, serie, tab, title;
      action = $(this).attr('action');
      tab = $(this).serializeArray();
      link = '';
      title = 'bdloc_appbundle_book[title]';
      illustrator = 'bdloc_appbundle_book[illustrator]';
      serie = 'bdloc_appbundle_book[serie][]';
      len = tab.length;
      action += '/1';
      $(tab).each(function(index) {
        var name, selector, texte, value;
        name = $(this).attr('name');
        value = $(this).attr('value');
        if (value === "") {
          console.log('vide');
        } else {
          console.log('pas vide');
        }
        if (name === title) {
          if (value === "") {
            return action += '/all' + value;
          } else {
            return action += '/' + value;
          }
        } else if (name === illustrator) {
          if (value === "") {
            return action += '/all' + value;
          } else {
            return action += '/' + value;
          }
        } else if (name === serie) {
          selector = '#bdloc_appbundle_book_serie_' + value;
          texte = $(selector).parent().text().trim();
          if (index === len - 2) {
            return link += texte;
          } else {
            return link += texte + '-';
          }
        }
      });
      if (link !== "") {
        action += '/' + link;
      }
      return $(this).attr('action', action);
    });
  };

  return Click;

})();

ManageAjax = (function() {
  function ManageAjax() {
    this.start();
    this.end();
  }

  ManageAjax.prototype.start = function() {
    return $(document).ajaxStart(function() {
      return $('#main').hide({
        effect: 'slide',
        direction: 'left',
        easing: 'easeInExpo',
        duration: 800,
        complete: function() {
          var divMessage, divWait;
          divMessage = $('<div>').attr('class', 'messageAttente').css({
            'float': 'left'
          }).append($('<h1>').attr('class', 'patienceScale').text('patience'));
          divWait = $('<div>').addClass('wait').css({
            'marginLeft': '40px',
            'float': 'left'
          });
          $('<div>').attr('class', 'turn').css({
            'borderRadius': '50%',
            'width': '80px',
            'height': '80px',
            'border': '5px solid #679403',
            'position': 'absolute',
            'borderBottomColor': 'transparent'
          }).appendTo(divWait);
          $('<div>').attr('class', 'turnReverse').css({
            'border-radius': '50%',
            'width': '40px',
            'height': '40px',
            'border': '5px solid #679403',
            'borderTopColor': 'transparent',
            'position': 'relative',
            'left': '20px',
            'top': '20px'
          }).appendTo(divWait);
          return $('<div>').attr('id', 'patience').addClass('col-xs-offset-2 col-md-offset-4 col-md-8').css({
            'position': 'absolute',
            'top': '50%',
            'left': '0%',
            'width': '100%'
          }).append(divMessage).append(divWait).appendTo('body').show({
            effect: 'slide',
            direction: 'left',
            easing: 'easeInExpo',
            duration: 800
          });
        }
      });
    });
  };

  ManageAjax.prototype.end = function() {
    return $(document).ajaxComplete(function() {
      return $('#patience').hide({
        effect: 'slide',
        direction: 'left',
        easing: 'easeInExpo',
        duration: 800,
        complete: function() {
          return $('#main').show({
            effect: 'slide',
            direction: 'right',
            easing: 'easeInExpo',
            duration: 800,
            complete: function() {
              return $('#patience').remove();
            }
          });
        }
      });
    });
  };

  return ManageAjax;

})();

Abonnement = (function() {
  function Abonnement(value) {
    this.value = value;
  }

  Abonnement.prototype.focusAbo = function() {
    var span;
    span = $('#montantAbonnement').find('span');
    if (this.value === "mensuel") {
      span.text("L’abonnement mensuel coûte le prix d’une seule BD : 12€ TTC");
    } else {
      span.text("Un abonnement annuel permet d’économiser 2 mois : 120€ TTC");
    }
    this.afficheSubmit();
    return this.abonnementInHiddenInput(this.value);
  };

  Abonnement.prototype.paySubscription = function() {
    new ManageAjax();
    return $.ajax({
      url: this.value.attr('action'),
      type: this.value.attr('method'),
      data: this.value.serialize(),
      success: function(html) {
        var newMain;
        html = $(html);
        newMain = html.filter('#main');
        console.log(newMain.html());
        $('#main').empty().append(newMain);
        return console.log($('#main').html());
      }
    });
  };

  Abonnement.prototype.afficheSubmit = function() {
    var afficheCard, form, len;
    form = $('#formAbonnement');
    len = form.find(':submit').length;
    if (len === 0) {
      afficheCard = $('<button>').attr({
        'type': 'submit',
        'class': 'btn btn-success'
      }).text('étape suivante').appendTo('#formAbonnement');
      return new Click(form).submitAbonnement();
    }
  };

  Abonnement.prototype.showInfoCard = function() {
    $.ajax({
      url: this.value.attr('href'),
      success: function(html) {
        var div;
        html = $(html);
        div = $('.afficheCarte');
        return div.append(html).animate({
          opacity: 1
        });
      }
    });
    return $(this.value).fadeOut();
  };

  Abonnement.prototype.afficheCard = function() {
    return $.ajax({
      url: this.value.attr('action'),
      success: function(html) {
        var div, hfour;
        html = $(html);
        div = $('<div>');
        hfour = $('<h4>').text('Vos coordonnées bancaires');
        div.attr({
          'class': 'col-xs-12 col-md-6',
          'id': 'bancaires'
        });
        html.find('#bdloc_appbundle_creditcard_abonnement').val($('#formAbonnement input').val());
        div.append(hfour).append(html).appendTo($('#choixAbonnement'));
        return new Click($('#formPayment')).submitPaymentSubcription();
      }
    });
  };

  Abonnement.prototype.abonnementInHiddenInput = function(value) {
    this.value = value;
    return $('#bancaires').find('#bdloc_appbundle_creditcard_abonnement').val(this.value);
  };

  return Abonnement;

})();

ManageProfil = (function() {
  function ManageProfil(button) {
    this.button = button;
  }

  ManageProfil.prototype.ajaxUpdateProfil = function() {
    return $.ajax({
      url: this.button.attr('action'),
      type: this.button.attr('method'),
      data: this.button.serialize(),
      success: function(html) {
        var detailsAccount, newDetailsAccount;
        html = $(html);
        detailsAccount = $('#detailsAccount');
        newDetailsAccount = html.find('#detailsAccount').children();
        return detailsAccount.fadeOut({
          complete: function() {
            detailsAccount.empty().append(newDetailsAccount);
            return new Click($('#updateAccountUser')).buttonUpdateProfil();
          }
        }).fadeIn();
      }
    });
  };

  ManageProfil.prototype.ajaxAfficheOptions = function() {
    return $.ajax({
      url: this.button.attr('href'),
      success: function(html) {
        var newOptions, options;
        console.log(html);
        html = $(html);
        options = $('#options');
        newOptions = html.filter('#otherOptions');
        return options.fadeOut({
          complete: function() {
            options.empty().append(newOptions);
            return new Click($('#button')).buttonAfficheChangePassword();
          }
        }).fadeIn();
      }
    });
  };

  return ManageProfil;

})();

ChangePassword = (function() {
  function ChangePassword(button) {
    this.button = button;
  }

  ChangePassword.prototype.ajaxFormChangePassword = function() {
    return $.ajax({
      url: this.button.attr('action'),
      type: this.button.attr('method'),
      data: this.button.serialize(),
      success: function(html) {
        var div, newDiv, newForm;
        html = $(html);
        div = $('#formChangePassword');
        newDiv = html.find('#change');
        newForm = html.find('#changePassword');
        new Click(newForm).buttonChangePassword();
        return div.fadeOut({
          complete: function() {
            div.empty();
            div.append(newDiv);
            return new Click($('#backOptions')).buttonAfficheOptions();
          }
        }).fadeIn();
      }
    });
  };

  ChangePassword.prototype.ajax = function() {
    return $.ajax({
      url: this.button.attr('href'),
      success: function(html) {
        var options;
        options = $('#options');
        return options.fadeOut({
          complete: function() {
            options.empty().append($(html));
            new Click($('#changePassword')).buttonChangePassword();
            return new Click($('#backOptions')).buttonAfficheOptions();
          }
        }).fadeIn();
      }
    });
  };

  return ChangePassword;

})();

Cart = (function() {
  function Cart(button) {
    this.button = button;
  }

  Cart.prototype.ajax = function() {
    return $.ajax({
      url: this.button.attr('href'),
      success: function(html) {
        var addRemove, cart, newButton, panier;
        addRemove = $('#addRemove');
        panier = '#panier';
        html = $(html);
        cart = html.find(panier).text();
        $(panier).text(cart);
        newButton = html.find('#cart');
        new Click(newButton).buttonCart();
        return addRemove.fadeOut({
          duration: 800,
          complete: function() {
            $('#cart').remove();
            return addRemove.prepend(newButton);
          }
        }).fadeIn({
          duration: 800
        });
      }
    });
  };

  return Cart;

})();

$(function() {
  var init;
  return init = new Init();
});
