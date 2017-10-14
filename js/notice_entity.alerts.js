(function ($, Drupal) {

  'use strict';

  /**
   * Contains ..
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.notice_entity_alerts = {
    attach: function (context) {
      var el = $('.notice-alerts');
      // Apply the effect to element only once. Prevents this from being run
      // during AJAX calls.
      el.once('noticeAlerts', context).each(function() {
        Drupal.notice_entity_alerts.update(el);
      });
    }
  }

  /**
   * @namespace
   */
  Drupal.notice_entity_alerts = {};

  /**
   * Keep track of the date.
   */
  Drupal.notice_entity_alerts.date = Drupal.notice_entity.getDate('today');

  /**
   * Update notices.
   */
  Drupal.notice_entity_alerts.update = function(el) {

    var url = Drupal.notice_entity.getApiEndpoint(Drupal.notice_entity_alerts.date);
    var promise = Drupal.notice_entity.getNotices(url);

    promise.success(function (data) {
      if (data instanceof Array) {

        // Pluck the template HTML from the DOM so we can clone it.
        var src = el.children('div').remove().clone();

        // Turn each JSON object into a full-fledged HTML element!
        data.forEach(function(em) {
          // When we show an alert we always set a cookie that will hide
          // subsequent appearances for a period of time. If there is a close
          // button we will attach a click event that sets an additional
          // cookie that will never expire, therefore "permanently" hiding
          // the alert from the user.
          var wait = Drupal.notice_entity_alerts.inKey('notices_wait', em.id);
          var dismissed = Drupal.notice_entity_alerts.inKey('notices_dismissed', em.id);

          if (!wait && !dismissed) {
            var html = Drupal.notice_entity_alerts.updateDom(src, em);

            // Close button click event.
            $('.notice-' + em.id + ' button').click(function(e) {
              // Pass the entity ID to a function that sets the "dismissed"
              // cookie.
              var id = $(this).closest('[data-notice-alert]').attr('class').match(/\d+/);
              Drupal.notice_entity_alerts.setDismissedCookie(id);

              // Dismiss the alert if it contains the class.
              if ($(this).hasClass('do-dismiss')) {
                Drupal.notice_entity_alerts.dismissAlert(id);
              }
            });

            // Set the 'wait' cookie on all alerts so we don't annoy people.
            Drupal.notice_entity_alerts.setWaitCookie(em.id);

            // The wrapper element is hidden by default; display it in its glory.
            el.css('display', 'block');
          }
        });
      }
    })
    .fail(function(err) {
      // To do: handle errors.
    });
  }

  /**
   * Check if the notice id is contained within in a cookie value.
   *
   * @param {String} cookieId
   *   An string representing the cookie ID.
   * @param {Number} id
   *   An integer representing a notice entity ID.
   */
  Drupal.notice_entity_alerts.inKey = function (cookieId, id) {
    var cookie = $.cookie('Drupal.visitor.' + cookieId);
    if (typeof cookie != 'undefined') {
      var items = cookie ? cookie.split(/\./) : [];
      return items.indexOf(id) !== -1 ? true : false;
    }
    return false;
  }

  /**
   * Set a cookie to hide the alert that expires.
   *
   * This is set for every alert upon display. Key consists of notice ids
   * joined by periods; e.g. 1.2.3
   *
   * @param {Number} id
   *   An integer representing a notice entity ID.
   */
  Drupal.notice_entity_alerts.setWaitCookie = function (id) {

    var wait = Drupal.notice_entity_alerts.inKey('notices_wait', id);

    if (!wait) {

      var date = new Date();
      var minutes = 15;
      date.setTime(date.getTime() + (minutes * 60 * 1000));

      var key = id;
      var cookie = $.cookie('Drupal.visitor.notices_wait');
      if (cookie != null) {
        key = cookie + '.' + key;
      }

      var args = {
        path: drupalSettings.path.baseUrl,
        expires: date
      };

      $.cookie('Drupal.visitor.notices_wait', key, args);
    }
  }

  /**
   * Set a cookie to hide the alert that doesn't expire.
   *
   * This is triggered by clicking the button element. Key consists of notice
   * ids joined by periods; e.g. 1.2.3
   *
   * @param {Number} id
   *   An integer representing a notice entity ID.
   */
  Drupal.notice_entity_alerts.setDismissedCookie = function (id) {

    var dismissed = Drupal.notice_entity_alerts.inKey('notices_dismissed', id);

    if (!dismissed) {

      // Expire after 1 day. This is necessary for showing the same notice alert
      // on multiple days.
      var date = new Date();
      date.setDate(date.getDate() + 1);

      var key = id[0];
      var cookie = $.cookie('Drupal.visitor.notices_dismissed');
      if (cookie != null) {
        key = cookie + '.' + key;
      }

      var args = {
        path: drupalSettings.path.baseUrl,
        expires: date
      };

      $.cookie('Drupal.visitor.notices_dismissed', key, args);
    }
  }

  /**
   * Contains...
   *
   * @param {Object} em
   *   A returned JSON object representing a notice.
   */
  Drupal.notice_entity_alerts.updateDom = function (src, em) {
    // src may contain multiple elements if there is more than one instance of
    // this block. Use .first() to ensure only one element is cloned.
    var clone = src.clone().first();
    $(clone).attr('class', 'notice-' + em.id);
    $(clone).find('.name').html(em.name);
    $(clone).find('.text').html(em.text);
    // Make this alert accessible.
    $(clone).attr('role', 'alert');

    $('.notice-alerts').append(clone);
  }

  /**
   * Dismiss the alert.
   *
   * @param {Number} id
   *   An integer representing a notice entity ID.
   */
  Drupal.notice_entity_alerts.dismissAlert = function (id) {
    $('.notice-' + id).hide();
  }

})(jQuery, Drupal);

