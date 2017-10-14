(function ($, Drupal) {

  'use strict';

  /**
   * @namespace
   */
  Drupal.notice_entity = {};

  /**
   * Set the date.
   *
   * @param {String} date
   *   A date string in format CCYY-MM-DD.
   */
  Drupal.notice_entity.getDate = function (date) {
    if (date == 'today') {
      var date = new Date();
      var month = date.getMonth()+1;
      var day = date.getDate();
      var m = String('0' + month).slice(-2)
      var d = String('0' + day).slice(-2)
      return date.getFullYear() + "-" + m + "-" + d;
    }
    else {
      return date;
    }
  }

  /**
   * Get API endpoint so we can retrieve entity data in JSON format.
   *
   * @param {String} date
   *   A date string in format CCYY-MM-DD.
   */
  Drupal.notice_entity.getApiEndpoint = function (date) {
    return window.location.protocol + '//' + window.location.hostname + '/api/notices/' + date;
  }

  /**
   * Get notices data.
   *
   * @param {String} url
   *   The endpoint URL.
   */
  Drupal.notice_entity.getNotices = function (url) {
    return $.ajax({
      type: 'GET',
      url: url,
      dataType: 'json'
    });
  }

  /**
   * Contains...
   *
   * @param {Object} em
   *   A returned JSON object representing a notice.
   */
  Drupal.notice_entity.updateDom = function (src, em) {
    // src may contain multiple elements if there is more than one instance of
    // this block. Use .first() to ensure only one element is cloned.
    var clone = src.clone().first();
    $(clone).find('.name').html(em.name);
    $(clone).find('.text').html(em.text);
    $('.notices').append(clone);
  }

})(jQuery, Drupal);

