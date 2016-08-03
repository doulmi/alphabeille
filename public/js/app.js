<<<<<<< HEAD
/* ========================================================================
 * Bootstrap Dropdowns Enhancement: dropdowns-enhancement.js v3.1.1 (Beta 1)
 * http://behigh.github.io/bootstrap_dropdowns_enhancement/
 * ========================================================================
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */

(function($) {
    "use strict";

    var toggle   = '[data-toggle="dropdown"]',
        disabled = '.disabled, :disabled',
        backdrop = '.dropdown-backdrop',
        menuClass = 'dropdown-menu',
        subMenuClass = 'dropdown-submenu',
        namespace = '.bs.dropdown.data-api',
        eventNamespace = '.bs.dropdown',
        openClass = 'open',
        touchSupport = 'ontouchstart' in document.documentElement,
        opened;


    function Dropdown(element) {
        $(element).on('click' + eventNamespace, this.toggle)
    }

    var proto = Dropdown.prototype;

    proto.toggle = function(event) {
        var $element = $(this);

        if ($element.is(disabled)) return;

        var $parent = getParent($element);
        var isActive = $parent.hasClass(openClass);
        var isSubMenu = $parent.hasClass(subMenuClass);
        var menuTree = isSubMenu ? getSubMenuParents($parent) : null;

        closeOpened(event, menuTree);

        if (!isActive) {
            if (!menuTree)
                menuTree = [$parent];

            if (touchSupport && !$parent.closest('.navbar-nav').length && !menuTree[0].find(backdrop).length) {
                // if mobile we use a backdrop because click events don't delegate
                $('<div class="' + backdrop.substr(1) + '"/>').appendTo(menuTree[0]).on('click', closeOpened)
            }

            for (var i = 0, s = menuTree.length; i < s; i++) {
                if (!menuTree[i].hasClass(openClass)) {
                    menuTree[i].addClass(openClass);
                    positioning(menuTree[i].children('.' + menuClass), menuTree[i]);
                }
            }
            opened = menuTree[0];
        }

        return false;
    };

    proto.keydown = function (e) {
        if (!/(38|40|27)/.test(e.keyCode)) return;

        var $this = $(this);

        e.preventDefault();
        e.stopPropagation();

        if ($this.is('.disabled, :disabled')) return;

        var $parent = getParent($this);
        var isActive = $parent.hasClass('open');

        if (!isActive || (isActive && e.keyCode == 27)) {
            if (e.which == 27) $parent.find(toggle).trigger('focus');
            return $this.trigger('click')
        }

        var desc = ' li:not(.divider):visible a';
        var desc1 = 'li:not(.divider):visible > input:not(disabled) ~ label';
        var $items = $parent.find(desc1 + ', ' + '[role="menu"]' + desc + ', [role="listbox"]' + desc);

        if (!$items.length) return;

        var index = $items.index($items.filter(':focus'));

        if (e.keyCode == 38 && index > 0)                 index--;                        // up
        if (e.keyCode == 40 && index < $items.length - 1) index++;                        // down
        if (!~index)                                      index = 0;

        $items.eq(index).trigger('focus')
    };

    proto.change = function (e) {

        var
            $parent,
            $menu,
            $toggle,
            selector,
            text = '',
            $items;

        $menu = $(this).closest('.' + menuClass);

        $toggle = $menu.parent().find('[data-label-placement]');

        if (!$toggle || !$toggle.length) {
            $toggle = $menu.parent().find(toggle);
        }

        if (!$toggle || !$toggle.length || $toggle.data('placeholder') === false)
            return; // do nothing, no control

        ($toggle.data('placeholder') == undefined && $toggle.data('placeholder', $.trim($toggle.text())));
        text = $.data($toggle[0], 'placeholder');

        $items = $menu.find('li > input:checked');

        if ($items.length) {
            text = [];
            $items.each(function () {
                var str = $(this).parent().find('label').eq(0),
                    label = str.find('.data-label');

                if (label.length) {
                    var p = $('<p></p>');
                    p.append(label.clone());
                    str = p.html();
                }
                else {
                    str = str.html();
                }


                str && text.push($.trim(str));
            });

            text = text.length < 4 ? text.join(', ') : text.length + ' selected';
        }

        var caret = $toggle.find('.caret');

        $toggle.html(text || '&nbsp;');
        if (caret.length)
            $toggle.append(' ') && caret.appendTo($toggle);

    };

    function positioning($menu, $control) {
        if ($menu.hasClass('pull-center')) {
            $menu.css('margin-right', $menu.outerWidth() / -2);
        }

        if ($menu.hasClass('pull-middle')) {
            $menu.css('margin-top', ($menu.outerHeight() / -2) - ($control.outerHeight() / 2));
        }
    }

    function closeOpened(event, menuTree) {
        if (opened) {

            if (!menuTree) {
                menuTree = [opened];
            }

            var parent;

            if (opened[0] !== menuTree[0][0]) {
                parent = opened;
            } else {
                parent = menuTree[menuTree.length - 1];
                if (parent.parent().hasClass(menuClass)) {
                    parent = parent.parent();
                }
            }

            parent.find('.' + openClass).removeClass(openClass);

            if (parent.hasClass(openClass))
                parent.removeClass(openClass);

            if (parent === opened) {
                opened = null;
                $(backdrop).remove();
            }
        }
    }

    function getSubMenuParents($submenu) {
        var result = [$submenu];
        var $parent;
        while (!$parent || $parent.hasClass(subMenuClass)) {
            $parent = ($parent || $submenu).parent();
            if ($parent.hasClass(menuClass)) {
                $parent = $parent.parent();
            }
            if ($parent.children(toggle)) {
                result.unshift($parent);
            }
        }
        return result;
    }

    function getParent($this) {
        var selector = $this.attr('data-target');

        if (!selector) {
            selector = $this.attr('href');
            selector = selector && /#[A-Za-z]/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, ''); //strip for ie7
        }

        var $parent = selector && $(selector);

        return $parent && $parent.length ? $parent : $this.parent()
    }

    // DROPDOWN PLUGIN DEFINITION
    // ==========================

    var old = $.fn.dropdown;

    $.fn.dropdown = function (option) {
        return this.each(function () {
            var $this = $(this);
            var data = $this.data('bs.dropdown');

            if (!data) $this.data('bs.dropdown', (data = new Dropdown(this)));
            if (typeof option == 'string') data[option].call($this);
        })
    };

    $.fn.dropdown.Constructor = Dropdown;

    $.fn.dropdown.clearMenus = function(e) {
        $(backdrop).remove();
        $('.' + openClass + ' ' + toggle).each(function () {
            var $parent = getParent($(this));
            var relatedTarget = { relatedTarget: this };
            if (!$parent.hasClass('open')) return;
            $parent.trigger(e = $.Event('hide' + eventNamespace, relatedTarget));
            if (e.isDefaultPrevented()) return;
            $parent.removeClass('open').trigger('hidden' + eventNamespace, relatedTarget);
        });
        return this;
    };


    // DROPDOWN NO CONFLICT
    // ====================

    $.fn.dropdown.noConflict = function () {
        $.fn.dropdown = old;
        return this
    };


    $(document).off(namespace)
        .on('click' + namespace, closeOpened)
        .on('click' + namespace, toggle, proto.toggle)
        .on('click' + namespace, '.dropdown-menu > li > input[type="checkbox"] ~ label, .dropdown-menu > li > input[type="checkbox"], .dropdown-menu.noclose > li', function (e) {
            e.stopPropagation()
        })
        .on('change' + namespace, '.dropdown-menu > li > input[type="checkbox"], .dropdown-menu > li > input[type="radio"]', proto.change)
        .on('keydown' + namespace, toggle + ', [role="menu"], [role="listbox"]', proto.keydown)
}(jQuery));
$(document).ready(function () {
    fullscreenSupport();
});

$(window).resize(function () {
    fullscreenSupport();
});

var fullscreenSupport = function () {
    var height = $(window).height();
    var width = $(window).width();
    $(".fullscreen").css('min-height', height);
};
/* ========================================================================
 * Bootstrap: tooltip.js v3.3.6
 * http://getbootstrap.com/javascript/#tooltip
 * Inspired by the original jQuery.tipsy by Jason Frame
 * ========================================================================
 * Copyright 2011-2016 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


+function ($) {
  'use strict';

  // TOOLTIP PUBLIC CLASS DEFINITION
  // ===============================

  var Tooltip = function (element, options) {
    this.type       = null
    this.options    = null
    this.enabled    = null
    this.timeout    = null
    this.hoverState = null
    this.$element   = null
    this.inState    = null

    this.init('tooltip', element, options)
  }

  Tooltip.VERSION  = '3.3.6'

  Tooltip.TRANSITION_DURATION = 150

  Tooltip.DEFAULTS = {
    animation: true,
    placement: 'top',
    selector: false,
    template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
    trigger: 'hover focus',
    title: '',
    delay: 0,
    html: false,
    container: false,
    viewport: {
      selector: 'body',
      padding: 0
    }
  }

  Tooltip.prototype.init = function (type, element, options) {
    this.enabled   = true
    this.type      = type
    this.$element  = $(element)
    this.options   = this.getOptions(options)
    this.$viewport = this.options.viewport && $($.isFunction(this.options.viewport) ? this.options.viewport.call(this, this.$element) : (this.options.viewport.selector || this.options.viewport))
    this.inState   = { click: false, hover: false, focus: false }

    if (this.$element[0] instanceof document.constructor && !this.options.selector) {
      throw new Error('`selector` option must be specified when initializing ' + this.type + ' on the window.document object!')
    }

    var triggers = this.options.trigger.split(' ')

    for (var i = triggers.length; i--;) {
      var trigger = triggers[i]

      if (trigger == 'click') {
        this.$element.on('click.' + this.type, this.options.selector, $.proxy(this.toggle, this))
      } else if (trigger != 'manual') {
        var eventIn  = trigger == 'hover' ? 'mouseenter' : 'focusin'
        var eventOut = trigger == 'hover' ? 'mouseleave' : 'focusout'

        this.$element.on(eventIn  + '.' + this.type, this.options.selector, $.proxy(this.enter, this))
        this.$element.on(eventOut + '.' + this.type, this.options.selector, $.proxy(this.leave, this))
      }
    }

    this.options.selector ?
      (this._options = $.extend({}, this.options, { trigger: 'manual', selector: '' })) :
      this.fixTitle()
  }

  Tooltip.prototype.getDefaults = function () {
    return Tooltip.DEFAULTS
  }

  Tooltip.prototype.getOptions = function (options) {
    options = $.extend({}, this.getDefaults(), this.$element.data(), options)

    if (options.delay && typeof options.delay == 'number') {
      options.delay = {
        show: options.delay,
        hide: options.delay
      }
    }

    return options
  }

  Tooltip.prototype.getDelegateOptions = function () {
    var options  = {}
    var defaults = this.getDefaults()

    this._options && $.each(this._options, function (key, value) {
      if (defaults[key] != value) options[key] = value
    })

    return options
  }

  Tooltip.prototype.enter = function (obj) {
    var self = obj instanceof this.constructor ?
      obj : $(obj.currentTarget).data('bs.' + this.type)

    if (!self) {
      self = new this.constructor(obj.currentTarget, this.getDelegateOptions())
      $(obj.currentTarget).data('bs.' + this.type, self)
    }

    if (obj instanceof $.Event) {
      self.inState[obj.type == 'focusin' ? 'focus' : 'hover'] = true
    }

    if (self.tip().hasClass('in') || self.hoverState == 'in') {
      self.hoverState = 'in'
      return
    }

    clearTimeout(self.timeout)

    self.hoverState = 'in'

    if (!self.options.delay || !self.options.delay.show) return self.show()

    self.timeout = setTimeout(function () {
      if (self.hoverState == 'in') self.show()
    }, self.options.delay.show)
  }

  Tooltip.prototype.isInStateTrue = function () {
    for (var key in this.inState) {
      if (this.inState[key]) return true
    }

    return false
  }

  Tooltip.prototype.leave = function (obj) {
    var self = obj instanceof this.constructor ?
      obj : $(obj.currentTarget).data('bs.' + this.type)

    if (!self) {
      self = new this.constructor(obj.currentTarget, this.getDelegateOptions())
      $(obj.currentTarget).data('bs.' + this.type, self)
    }

    if (obj instanceof $.Event) {
      self.inState[obj.type == 'focusout' ? 'focus' : 'hover'] = false
    }

    if (self.isInStateTrue()) return

    clearTimeout(self.timeout)

    self.hoverState = 'out'

    if (!self.options.delay || !self.options.delay.hide) return self.hide()

    self.timeout = setTimeout(function () {
      if (self.hoverState == 'out') self.hide()
    }, self.options.delay.hide)
  }

  Tooltip.prototype.show = function () {
    var e = $.Event('show.bs.' + this.type)

    if (this.hasContent() && this.enabled) {
      this.$element.trigger(e)

      var inDom = $.contains(this.$element[0].ownerDocument.documentElement, this.$element[0])
      if (e.isDefaultPrevented() || !inDom) return
      var that = this

      var $tip = this.tip()

      var tipId = this.getUID(this.type)

      this.setContent()
      $tip.attr('id', tipId)
      this.$element.attr('aria-describedby', tipId)

      if (this.options.animation) $tip.addClass('fade')

      var placement = typeof this.options.placement == 'function' ?
        this.options.placement.call(this, $tip[0], this.$element[0]) :
        this.options.placement

      var autoToken = /\s?auto?\s?/i
      var autoPlace = autoToken.test(placement)
      if (autoPlace) placement = placement.replace(autoToken, '') || 'top'

      $tip
        .detach()
        .css({ top: 0, left: 0, display: 'block' })
        .addClass(placement)
        .data('bs.' + this.type, this)

      this.options.container ? $tip.appendTo(this.options.container) : $tip.insertAfter(this.$element)
      this.$element.trigger('inserted.bs.' + this.type)

      var pos          = this.getPosition()
      var actualWidth  = $tip[0].offsetWidth
      var actualHeight = $tip[0].offsetHeight

      if (autoPlace) {
        var orgPlacement = placement
        var viewportDim = this.getPosition(this.$viewport)

        placement = placement == 'bottom' && pos.bottom + actualHeight > viewportDim.bottom ? 'top'    :
                    placement == 'top'    && pos.top    - actualHeight < viewportDim.top    ? 'bottom' :
                    placement == 'right'  && pos.right  + actualWidth  > viewportDim.width  ? 'left'   :
                    placement == 'left'   && pos.left   - actualWidth  < viewportDim.left   ? 'right'  :
                    placement

        $tip
          .removeClass(orgPlacement)
          .addClass(placement)
      }

      var calculatedOffset = this.getCalculatedOffset(placement, pos, actualWidth, actualHeight)

      this.applyPlacement(calculatedOffset, placement)

      var complete = function () {
        var prevHoverState = that.hoverState
        that.$element.trigger('shown.bs.' + that.type)
        that.hoverState = null

        if (prevHoverState == 'out') that.leave(that)
      }

      $.support.transition && this.$tip.hasClass('fade') ?
        $tip
          .one('bsTransitionEnd', complete)
          .emulateTransitionEnd(Tooltip.TRANSITION_DURATION) :
        complete()
    }
  }

  Tooltip.prototype.applyPlacement = function (offset, placement) {
    var $tip   = this.tip()
    var width  = $tip[0].offsetWidth
    var height = $tip[0].offsetHeight

    // manually read margins because getBoundingClientRect includes difference
    var marginTop = parseInt($tip.css('margin-top'), 10)
    var marginLeft = parseInt($tip.css('margin-left'), 10)

    // we must check for NaN for ie 8/9
    if (isNaN(marginTop))  marginTop  = 0
    if (isNaN(marginLeft)) marginLeft = 0

    offset.top  += marginTop
    offset.left += marginLeft

    // $.fn.offset doesn't round pixel values
    // so we use setOffset directly with our own function B-0
    $.offset.setOffset($tip[0], $.extend({
      using: function (props) {
        $tip.css({
          top: Math.round(props.top),
          left: Math.round(props.left)
        })
      }
    }, offset), 0)

    $tip.addClass('in')

    // check to see if placing tip in new offset caused the tip to resize itself
    var actualWidth  = $tip[0].offsetWidth
    var actualHeight = $tip[0].offsetHeight

    if (placement == 'top' && actualHeight != height) {
      offset.top = offset.top + height - actualHeight
    }

    var delta = this.getViewportAdjustedDelta(placement, offset, actualWidth, actualHeight)

    if (delta.left) offset.left += delta.left
    else offset.top += delta.top

    var isVertical          = /top|bottom/.test(placement)
    var arrowDelta          = isVertical ? delta.left * 2 - width + actualWidth : delta.top * 2 - height + actualHeight
    var arrowOffsetPosition = isVertical ? 'offsetWidth' : 'offsetHeight'

    $tip.offset(offset)
    this.replaceArrow(arrowDelta, $tip[0][arrowOffsetPosition], isVertical)
  }

  Tooltip.prototype.replaceArrow = function (delta, dimension, isVertical) {
    this.arrow()
      .css(isVertical ? 'left' : 'top', 50 * (1 - delta / dimension) + '%')
      .css(isVertical ? 'top' : 'left', '')
  }

  Tooltip.prototype.setContent = function () {
    var $tip  = this.tip()
    var title = this.getTitle()

    $tip.find('.tooltip-inner')[this.options.html ? 'html' : 'text'](title)
    $tip.removeClass('fade in top bottom left right')
  }

  Tooltip.prototype.hide = function (callback) {
    var that = this
    var $tip = $(this.$tip)
    var e    = $.Event('hide.bs.' + this.type)

    function complete() {
      if (that.hoverState != 'in') $tip.detach()
      that.$element
        .removeAttr('aria-describedby')
        .trigger('hidden.bs.' + that.type)
      callback && callback()
    }

    this.$element.trigger(e)

    if (e.isDefaultPrevented()) return

    $tip.removeClass('in')

    $.support.transition && $tip.hasClass('fade') ?
      $tip
        .one('bsTransitionEnd', complete)
        .emulateTransitionEnd(Tooltip.TRANSITION_DURATION) :
      complete()

    this.hoverState = null

    return this
  }

  Tooltip.prototype.fixTitle = function () {
    var $e = this.$element
    if ($e.attr('title') || typeof $e.attr('data-original-title') != 'string') {
      $e.attr('data-original-title', $e.attr('title') || '').attr('title', '')
    }
  }

  Tooltip.prototype.hasContent = function () {
    return this.getTitle()
  }

  Tooltip.prototype.getPosition = function ($element) {
    $element   = $element || this.$element

    var el     = $element[0]
    var isBody = el.tagName == 'BODY'

    var elRect    = el.getBoundingClientRect()
    if (elRect.width == null) {
      // width and height are missing in IE8, so compute them manually; see https://github.com/twbs/bootstrap/issues/14093
      elRect = $.extend({}, elRect, { width: elRect.right - elRect.left, height: elRect.bottom - elRect.top })
    }
    var elOffset  = isBody ? { top: 0, left: 0 } : $element.offset()
    var scroll    = { scroll: isBody ? document.documentElement.scrollTop || document.body.scrollTop : $element.scrollTop() }
    var outerDims = isBody ? { width: $(window).width(), height: $(window).height() } : null

    return $.extend({}, elRect, scroll, outerDims, elOffset)
  }

  Tooltip.prototype.getCalculatedOffset = function (placement, pos, actualWidth, actualHeight) {
    return placement == 'bottom' ? { top: pos.top + pos.height,   left: pos.left + pos.width / 2 - actualWidth / 2 } :
           placement == 'top'    ? { top: pos.top - actualHeight, left: pos.left + pos.width / 2 - actualWidth / 2 } :
           placement == 'left'   ? { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left - actualWidth } :
        /* placement == 'right' */ { top: pos.top + pos.height / 2 - actualHeight / 2, left: pos.left + pos.width }

  }

  Tooltip.prototype.getViewportAdjustedDelta = function (placement, pos, actualWidth, actualHeight) {
    var delta = { top: 0, left: 0 }
    if (!this.$viewport) return delta

    var viewportPadding = this.options.viewport && this.options.viewport.padding || 0
    var viewportDimensions = this.getPosition(this.$viewport)

    if (/right|left/.test(placement)) {
      var topEdgeOffset    = pos.top - viewportPadding - viewportDimensions.scroll
      var bottomEdgeOffset = pos.top + viewportPadding - viewportDimensions.scroll + actualHeight
      if (topEdgeOffset < viewportDimensions.top) { // top overflow
        delta.top = viewportDimensions.top - topEdgeOffset
      } else if (bottomEdgeOffset > viewportDimensions.top + viewportDimensions.height) { // bottom overflow
        delta.top = viewportDimensions.top + viewportDimensions.height - bottomEdgeOffset
      }
    } else {
      var leftEdgeOffset  = pos.left - viewportPadding
      var rightEdgeOffset = pos.left + viewportPadding + actualWidth
      if (leftEdgeOffset < viewportDimensions.left) { // left overflow
        delta.left = viewportDimensions.left - leftEdgeOffset
      } else if (rightEdgeOffset > viewportDimensions.right) { // right overflow
        delta.left = viewportDimensions.left + viewportDimensions.width - rightEdgeOffset
      }
    }

    return delta
  }

  Tooltip.prototype.getTitle = function () {
    var title
    var $e = this.$element
    var o  = this.options

    title = $e.attr('data-original-title')
      || (typeof o.title == 'function' ? o.title.call($e[0]) :  o.title)

    return title
  }

  Tooltip.prototype.getUID = function (prefix) {
    do prefix += ~~(Math.random() * 1000000)
    while (document.getElementById(prefix))
    return prefix
  }

  Tooltip.prototype.tip = function () {
    if (!this.$tip) {
      this.$tip = $(this.options.template)
      if (this.$tip.length != 1) {
        throw new Error(this.type + ' `template` option must consist of exactly 1 top-level element!')
      }
    }
    return this.$tip
  }

  Tooltip.prototype.arrow = function () {
    return (this.$arrow = this.$arrow || this.tip().find('.tooltip-arrow'))
  }

  Tooltip.prototype.enable = function () {
    this.enabled = true
  }

  Tooltip.prototype.disable = function () {
    this.enabled = false
  }

  Tooltip.prototype.toggleEnabled = function () {
    this.enabled = !this.enabled
  }

  Tooltip.prototype.toggle = function (e) {
    var self = this
    if (e) {
      self = $(e.currentTarget).data('bs.' + this.type)
      if (!self) {
        self = new this.constructor(e.currentTarget, this.getDelegateOptions())
        $(e.currentTarget).data('bs.' + this.type, self)
      }
    }

    if (e) {
      self.inState.click = !self.inState.click
      if (self.isInStateTrue()) self.enter(self)
      else self.leave(self)
    } else {
      self.tip().hasClass('in') ? self.leave(self) : self.enter(self)
    }
  }

  Tooltip.prototype.destroy = function () {
    var that = this
    clearTimeout(this.timeout)
    this.hide(function () {
      that.$element.off('.' + that.type).removeData('bs.' + that.type)
      if (that.$tip) {
        that.$tip.detach()
      }
      that.$tip = null
      that.$arrow = null
      that.$viewport = null
    })
  }


  // TOOLTIP PLUGIN DEFINITION
  // =========================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this)
      var data    = $this.data('bs.tooltip')
      var options = typeof option == 'object' && option

      if (!data && /destroy|hide/.test(option)) return
      if (!data) $this.data('bs.tooltip', (data = new Tooltip(this, options)))
      if (typeof option == 'string') data[option]()
    })
  }

  var old = $.fn.tooltip

  $.fn.tooltip             = Plugin
  $.fn.tooltip.Constructor = Tooltip


  // TOOLTIP NO CONFLICT
  // ===================

  $.fn.tooltip.noConflict = function () {
    $.fn.tooltip = old
    return this
  }

}(jQuery);

(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-79348501-1', 'auto');
ga('send', 'pageview');
!function(e){e(["jquery"],function(e){return function(){function t(e,t,n){return f({type:O.error,iconClass:g().iconClasses.error,message:e,optionsOverride:n,title:t})}function n(t,n){return t||(t=g()),v=e("#"+t.containerId),v.length?v:(n&&(v=c(t)),v)}function i(e,t,n){return f({type:O.info,iconClass:g().iconClasses.info,message:e,optionsOverride:n,title:t})}function o(e){w=e}function s(e,t,n){return f({type:O.success,iconClass:g().iconClasses.success,message:e,optionsOverride:n,title:t})}function a(e,t,n){return f({type:O.warning,iconClass:g().iconClasses.warning,message:e,optionsOverride:n,title:t})}function r(e){var t=g();v||n(t),l(e,t)||u(t)}function d(t){var i=g();return v||n(i),t&&0===e(":focus",t).length?void h(t):void(v.children().length&&v.remove())}function u(t){for(var n=v.children(),i=n.length-1;i>=0;i--)l(e(n[i]),t)}function l(t,n){return t&&0===e(":focus",t).length?(t[n.hideMethod]({duration:n.hideDuration,easing:n.hideEasing,complete:function(){h(t)}}),!0):!1}function c(t){return v=e("<div/>").attr("id",t.containerId).addClass(t.positionClass).attr("aria-live","polite").attr("role","alert"),v.appendTo(e(t.target)),v}function p(){return{tapToDismiss:!0,toastClass:"toast",containerId:"toast-container",debug:!1,showMethod:"fadeIn",showDuration:300,showEasing:"swing",onShown:void 0,hideMethod:"fadeOut",hideDuration:1e3,hideEasing:"swing",onHidden:void 0,extendedTimeOut:1e3,iconClasses:{error:"toast-error",info:"toast-info",success:"toast-success",warning:"toast-warning"},iconClass:"toast-info",positionClass:"toast-top-right",timeOut:5e3,titleClass:"toast-title",messageClass:"toast-message",target:"body",closeHtml:'<button type="button">&times;</button>',newestOnTop:!0,preventDuplicates:!1,progressBar:!1}}function m(e){w&&w(e)}function f(t){function i(t){return!e(":focus",l).length||t?(clearTimeout(O.intervalId),l[r.hideMethod]({duration:r.hideDuration,easing:r.hideEasing,complete:function(){h(l),r.onHidden&&"hidden"!==b.state&&r.onHidden(),b.state="hidden",b.endTime=new Date,m(b)}})):void 0}function o(){(r.timeOut>0||r.extendedTimeOut>0)&&(u=setTimeout(i,r.extendedTimeOut),O.maxHideTime=parseFloat(r.extendedTimeOut),O.hideEta=(new Date).getTime()+O.maxHideTime)}function s(){clearTimeout(u),O.hideEta=0,l.stop(!0,!0)[r.showMethod]({duration:r.showDuration,easing:r.showEasing})}function a(){var e=(O.hideEta-(new Date).getTime())/O.maxHideTime*100;f.width(e+"%")}var r=g(),d=t.iconClass||r.iconClass;if("undefined"!=typeof t.optionsOverride&&(r=e.extend(r,t.optionsOverride),d=t.optionsOverride.iconClass||d),r.preventDuplicates){if(t.message===C)return;C=t.message}T++,v=n(r,!0);var u=null,l=e("<div/>"),c=e("<div/>"),p=e("<div/>"),f=e("<div/>"),w=e(r.closeHtml),O={intervalId:null,hideEta:null,maxHideTime:null},b={toastId:T,state:"visible",startTime:new Date,options:r,map:t};return t.iconClass&&l.addClass(r.toastClass).addClass(d),t.title&&(c.append(t.title).addClass(r.titleClass),l.append(c)),t.message&&(p.append(t.message).addClass(r.messageClass),l.append(p)),r.closeButton&&(w.addClass("toast-close-button").attr("role","button"),l.prepend(w)),r.progressBar&&(f.addClass("toast-progress"),l.prepend(f)),l.hide(),r.newestOnTop?v.prepend(l):v.append(l),l[r.showMethod]({duration:r.showDuration,easing:r.showEasing,complete:r.onShown}),r.timeOut>0&&(u=setTimeout(i,r.timeOut),O.maxHideTime=parseFloat(r.timeOut),O.hideEta=(new Date).getTime()+O.maxHideTime,r.progressBar&&(O.intervalId=setInterval(a,10))),l.hover(s,o),!r.onclick&&r.tapToDismiss&&l.click(i),r.closeButton&&w&&w.click(function(e){e.stopPropagation?e.stopPropagation():void 0!==e.cancelBubble&&e.cancelBubble!==!0&&(e.cancelBubble=!0),i(!0)}),r.onclick&&l.click(function(){r.onclick(),i()}),m(b),r.debug&&console&&console.log(b),l}function g(){return e.extend({},p(),b.options)}function h(e){v||(v=n()),e.is(":visible")||(e.remove(),e=null,0===v.children().length&&(v.remove(),C=void 0))}var v,w,C,T=0,O={error:"error",info:"info",success:"success",warning:"warning"},b={clear:r,remove:d,error:t,getContainer:n,info:i,options:{},subscribe:o,success:s,version:"2.1.0",warning:a};return b}()})}("function"==typeof define&&define.amd?define:function(e,t){"undefined"!=typeof module&&module.exports?module.exports=t(require("jquery")):window.toastr=t(window.jQuery)});
//# sourceMappingURL=toastr.js.map
/*!
 * jQuery goTop v1.1.2 (https://github.com/scottdorman/jquery-gotop)
 * Copyright 2015 Scott Dorman (@sdorman)
 * Licensed under MIT (https://github.com/scottdorman/jquery-gotop/blob/master/LICENSE)
 * Adapted from goUp originally developed by Roger Vila (@_rogervila)
 */
(function ($) {
    $.fn.goTop = function (options) {

        $.fn.goTop.defaults = {
            appear: 200,
            scrolltime: 800,
            src: "glyphicon glyphicon-chevron-up",
            width: 45,
            place: "right",
            fadein: 500,
            fadeout: 500,
            opacity: 0.5,
            marginX: 2,
            marginY: 2
        };

        var opts = $.extend({}, $.fn.goTop.defaults, options);

        return this.each(function () {
            var g = $(this);
            g.html("<a id='goTopAnchor'><span id='goTopSpan' /></a>");

            var ga = g.children('a');
            var gs = ga.children('span');

            var css = {
                "position": "fixed",
                "display": "block",
                "width": "'" + opts.width + "px'",
                "z-index": "9",
                "bottom": opts.marginY + "%"
            };

            css[opts.place === "left" ? "left" : "right"] = opts.marginX + "%";

            g.css(css);

            //opacity
            ga.css("opacity", opts.opacity);
            gs.addClass(opts.src);
            gs.css("font-size", opts.width);
            gs.hide();

            //appear, fadein, fadeout
            $(function () {
                $(window).scroll(function () {
                    if ($(this).scrollTop() > opts.appear) {
                        gs.fadeIn(opts.fadein);
                    }
                    else {
                        gs.fadeOut(opts.fadeout);
                    }
                });

                //hover effect
                $(ga).hover(function () {
                    $(this).css("opacity", "1.0");
                    $(this).css("cursor", "pointer");
                    $(this).css("color", "#273041")
                }, function () {
                    $(this).css("opacity", opts.opacity);
                });

                //scrolltime
                $(ga).click(function () {
                    $('body,html').animate({
                        scrollTop: 0
                    }, opts.scrolltime);
                    return false;
                });
            });
        });
    };
})(jQuery);

/* ===================================================
 * bootstrap-markdown.js v2.10.0
 * http://github.com/toopay/bootstrap-markdown
 * ===================================================
 * Copyright 2013-2016 Taufan Aditya
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================== */
(function(factory) {
  if (typeof define === "function" && define.amd) {
    //RequireJS
    define(["jquery"], factory);
  } else if (typeof exports === 'object') {
    //Backbone.js
    factory(require('jquery'));
  } else {
    //Jquery plugin
    factory(jQuery);
  }
}(function($) {
  "use strict";

  /* MARKDOWN CLASS DEFINITION
   * ========================== */

  var Markdown = function(element, options) {
    // @TODO : remove this BC on next major release
    // @see : https://github.com/toopay/bootstrap-markdown/issues/109
    var opts = ['autofocus', 'savable', 'hideable', 'width',
      'height', 'resize', 'iconlibrary', 'language',
      'footer', 'fullscreen', 'hiddenButtons', 'disabledButtons'
    ];
    $.each(opts, function(_, opt) {
      if (typeof $(element).data(opt) !== 'undefined') {
        options = typeof options == 'object' ? options : {};
        options[opt] = $(element).data(opt);
      }
    });
    // End BC

    // Class Properties
    this.$ns = 'bootstrap-markdown';
    this.$element = $(element);
    this.$editable = {
      el: null,
      type: null,
      attrKeys: [],
      attrValues: [],
      content: null
    };
    this.$options = $.extend(true, {}, $.fn.markdown.defaults, options, this.$element.data('options'));
    this.$oldContent = null;
    this.$isPreview = false;
    this.$isFullscreen = false;
    this.$editor = null;
    this.$textarea = null;
    this.$handler = [];
    this.$callback = [];
    this.$nextTab = [];

    this.showEditor();
  };

  Markdown.prototype = {

    constructor: Markdown,
    __alterButtons: function(name, alter) {
      var handler = this.$handler,
        isAll = (name == 'all'),
        that = this;

      $.each(handler, function(k, v) {
        var halt = true;
        if (isAll) {
          halt = false;
        } else {
          halt = v.indexOf(name) < 0;
        }

        if (halt === false) {
          alter(that.$editor.find('button[data-handler="' + v + '"]'));
        }
      });
    },
    __buildButtons: function(buttonsArray, container) {
      var i,
        ns = this.$ns,
        handler = this.$handler,
        callback = this.$callback;

      for (i = 0; i < buttonsArray.length; i++) {
        // Build each group container
        var y, btnGroups = buttonsArray[i];
        for (y = 0; y < btnGroups.length; y++) {
          // Build each button group
          var z,
            buttons = btnGroups[y].data,
            btnGroupContainer = $('<div/>', {
              'class': 'btn-group'
            });

          for (z = 0; z < buttons.length; z++) {
            var button = buttons[z],
              buttonContainer, buttonIconContainer,
              buttonHandler = ns + '-' + button.name,
              buttonIcon = this.__getIcon(button.icon),
              btnText = button.btnText ? button.btnText : '',
              btnClass = button.btnClass ? button.btnClass : 'btn',
              tabIndex = button.tabIndex ? button.tabIndex : '-1',
              hotkey = typeof button.hotkey !== 'undefined' ? button.hotkey : '',
              hotkeyCaption = typeof jQuery.hotkeys !== 'undefined' && hotkey !== '' ? ' (' + hotkey + ')' : '';

            // Construct the button object
            buttonContainer = $('<button></button>');
            buttonContainer.text(' ' + this.__localize(btnText)).addClass('btn-default btn-sm').addClass(btnClass);
            if (btnClass.match(/btn\-(primary|success|info|warning|danger|link)/)) {
              buttonContainer.removeClass('btn-default');
            }
            buttonContainer.attr({
              'type': 'button',
              'title': this.__localize(button.title) + hotkeyCaption,
              'tabindex': tabIndex,
              'data-provider': ns,
              'data-handler': buttonHandler,
              'data-hotkey': hotkey
            });
            if (button.toggle === true) {
              buttonContainer.attr('data-toggle', 'button');
            }
            buttonIconContainer = $('<span/>');
            buttonIconContainer.addClass(buttonIcon);
            buttonIconContainer.prependTo(buttonContainer);

            // Attach the button object
            btnGroupContainer.append(buttonContainer);

            // Register handler and callback
            handler.push(buttonHandler);
            callback.push(button.callback);
          }

          // Attach the button group into container dom
          container.append(btnGroupContainer);
        }
      }

      return container;
    },
    __setListener: function() {
      // Set size and resizable Properties
      var hasRows = typeof this.$textarea.attr('rows') !== 'undefined',
        maxRows = this.$textarea.val().split("\n").length > 5 ? this.$textarea.val().split("\n").length : '5',
        rowsVal = hasRows ? this.$textarea.attr('rows') : maxRows;

      this.$textarea.attr('rows', rowsVal);
      if (this.$options.resize) {
        this.$textarea.css('resize', this.$options.resize);
      }

      this.$textarea.on({
        'focus': $.proxy(this.focus, this),
        'keyup': $.proxy(this.keyup, this),
        'change': $.proxy(this.change, this),
        'select': $.proxy(this.select, this)
      });

      if (this.eventSupported('keydown')) {
        this.$textarea.on('keydown', $.proxy(this.keydown, this));
      }

      if (this.eventSupported('keypress')) {
        this.$textarea.on('keypress', $.proxy(this.keypress, this));
      }

      // Re-attach markdown data
      this.$textarea.data('markdown', this);
    },
    __handle: function(e) {
      var target = $(e.currentTarget),
        handler = this.$handler,
        callback = this.$callback,
        handlerName = target.attr('data-handler'),
        callbackIndex = handler.indexOf(handlerName),
        callbackHandler = callback[callbackIndex];

      // Trigger the focusin
      $(e.currentTarget).focus();

      callbackHandler(this);

      // Trigger onChange for each button handle
      this.change(this);

      // Unless it was the save handler,
      // focusin the textarea
      if (handlerName.indexOf('cmdSave') < 0) {
        this.$textarea.focus();
      }

      e.preventDefault();
    },
    __localize: function(string) {
      var messages = $.fn.markdown.messages,
        language = this.$options.language;
      if (
        typeof messages !== 'undefined' &&
        typeof messages[language] !== 'undefined' &&
        typeof messages[language][string] !== 'undefined'
      ) {
        return messages[language][string];
      }
      return string;
    },
    __getIcon: function(src) {
      return typeof src == 'object' ? src[this.$options.iconlibrary] : src;
    },
    setFullscreen: function(mode) {
      var $editor = this.$editor,
        $textarea = this.$textarea;

      if (mode === true) {
        $editor.addClass('md-fullscreen-mode');
        $('body').addClass('md-nooverflow');
        this.$options.onFullscreen(this);
      } else {
        $editor.removeClass('md-fullscreen-mode');
        $('body').removeClass('md-nooverflow');
        this.$options.onFullscreenExit(this);

        if (this.$isPreview === true)
          this.hidePreview().showPreview();
      }

      this.$isFullscreen = mode;
      $textarea.focus();
    },
    showEditor: function() {
      var instance = this,
        textarea,
        ns = this.$ns,
        container = this.$element,
        originalHeigth = container.css('height'),
        originalWidth = container.css('width'),
        editable = this.$editable,
        handler = this.$handler,
        callback = this.$callback,
        options = this.$options,
        editor = $('<div/>', {
          'class': 'md-editor',
          click: function() {
            instance.focus();
          }
        });

      // Prepare the editor
      if (this.$editor === null) {
        // Create the panel
        var editorHeader = $('<div/>', {
          'class': 'md-header btn-toolbar'
        });

        // Merge the main & additional button groups together
        var allBtnGroups = [];
        if (options.buttons.length > 0) allBtnGroups = allBtnGroups.concat(options.buttons[0]);
        if (options.additionalButtons.length > 0) {
          // iterate the additional button groups
          $.each(options.additionalButtons[0], function(idx, buttonGroup) {

            // see if the group name of the addional group matches an existing group
            var matchingGroups = $.grep(allBtnGroups, function(allButtonGroup, allIdx) {
              return allButtonGroup.name === buttonGroup.name;
            });

            // if it matches add the addional buttons to that group, if not just add it to the all buttons group
            if (matchingGroups.length > 0) {
              matchingGroups[0].data = matchingGroups[0].data.concat(buttonGroup.data);
            } else {
              allBtnGroups.push(options.additionalButtons[0][idx]);
            }

          });
        }

        // Reduce and/or reorder the button groups
        if (options.reorderButtonGroups.length > 0) {
          allBtnGroups = allBtnGroups
            .filter(function(btnGroup) {
              return options.reorderButtonGroups.indexOf(btnGroup.name) > -1;
            })
            .sort(function(a, b) {
              if (options.reorderButtonGroups.indexOf(a.name) < options.reorderButtonGroups.indexOf(b.name)) return -1;
              if (options.reorderButtonGroups.indexOf(a.name) > options.reorderButtonGroups.indexOf(b.name)) return 1;
              return 0;
            });
        }

        // Build the buttons
        if (allBtnGroups.length > 0) {
          editorHeader = this.__buildButtons([allBtnGroups], editorHeader);
        }

        if (options.fullscreen.enable) {
          editorHeader.append('<div class="md-controls"><a class="md-control md-control-fullscreen" href="#"><span class="' + this.__getIcon(options.fullscreen.icons.fullscreenOn) + '"></span></a></div>').on('click', '.md-control-fullscreen', function(e) {
            e.preventDefault();
            instance.setFullscreen(true);
          });
        }

        editor.append(editorHeader);

        // Wrap the textarea
        if (container.is('textarea')) {
          container.before(editor);
          textarea = container;
          textarea.addClass('md-input');
          editor.append(textarea);
        } else {
          var rawContent = (typeof toMarkdown == 'function') ? toMarkdown(container.html()) : container.html(),
            currentContent = $.trim(rawContent);

          // This is some arbitrary content that could be edited
          textarea = $('<textarea/>', {
            'class': 'md-input',
            'val': currentContent
          });

          editor.append(textarea);

          // Save the editable
          editable.el = container;
          editable.type = container.prop('tagName').toLowerCase();
          editable.content = container.html();

          $(container[0].attributes).each(function() {
            editable.attrKeys.push(this.nodeName);
            editable.attrValues.push(this.nodeValue);
          });

          // Set editor to blocked the original container
          container.replaceWith(editor);
        }

        var editorFooter = $('<div/>', {
            'class': 'md-footer'
          }),
          createFooter = false,
          footer = '';
        // Create the footer if savable
        if (options.savable) {
          createFooter = true;
          var saveHandler = 'cmdSave';

          // Register handler and callback
          handler.push(saveHandler);
          callback.push(options.onSave);

          editorFooter.append('<button class="btn btn-success" data-provider="' +
            ns +
            '" data-handler="' +
            saveHandler +
            '"><i class="icon icon-white icon-ok"></i> ' +
            this.__localize('Save') +
            '</button>');


        }

        footer = typeof options.footer === 'function' ? options.footer(this) : options.footer;

        if ($.trim(footer) !== '') {
          createFooter = true;
          editorFooter.append(footer);
        }

        if (createFooter) editor.append(editorFooter);

        // Set width
        if (options.width && options.width !== 'inherit') {
          if (jQuery.isNumeric(options.width)) {
            editor.css('display', 'table');
            textarea.css('width', options.width + 'px');
          } else {
            editor.addClass(options.width);
          }
        }

        // Set height
        if (options.height && options.height !== 'inherit') {
          if (jQuery.isNumeric(options.height)) {
            var height = options.height;
            if (editorHeader) height = Math.max(0, height - editorHeader.outerHeight());
            if (editorFooter) height = Math.max(0, height - editorFooter.outerHeight());
            textarea.css('height', height + 'px');
          } else {
            editor.addClass(options.height);
          }
        }

        // Reference
        this.$editor = editor;
        this.$textarea = textarea;
        this.$editable = editable;
        this.$oldContent = this.getContent();

        this.__setListener();

        // Set editor attributes, data short-hand API and listener
        this.$editor.attr('id', (new Date()).getTime());
        this.$editor.on('click', '[data-provider="bootstrap-markdown"]', $.proxy(this.__handle, this));

        if (this.$element.is(':disabled') || this.$element.is('[readonly]')) {
          this.$editor.addClass('md-editor-disabled');
          this.disableButtons('all');
        }

        if (this.eventSupported('keydown') && typeof jQuery.hotkeys === 'object') {
          editorHeader.find('[data-provider="bootstrap-markdown"]').each(function() {
            var $button = $(this),
              hotkey = $button.attr('data-hotkey');
            if (hotkey.toLowerCase() !== '') {
              textarea.bind('keydown', hotkey, function() {
                $button.trigger('click');
                return false;
              });
            }
          });
        }

        if (options.initialstate === 'preview') {
          this.showPreview();
        } else if (options.initialstate === 'fullscreen' && options.fullscreen.enable) {
          this.setFullscreen(true);
        }

      } else {
        this.$editor.show();
      }

      if (options.autofocus) {
        this.$textarea.focus();
        this.$editor.addClass('active');
      }

      if (options.fullscreen.enable && options.fullscreen !== false) {
        this.$editor.append('<div class="md-fullscreen-controls">' +
          '<a href="#" class="exit-fullscreen" title="Exit fullscreen"><span class="' + this.__getIcon(options.fullscreen.icons.fullscreenOff) + '">' +
          '</span></a>' +
          '</div>');
        this.$editor.on('click', '.exit-fullscreen', function(e) {
          e.preventDefault();
          instance.setFullscreen(false);
        });
      }

      // hide hidden buttons from options
      this.hideButtons(options.hiddenButtons);

      // disable disabled buttons from options
      this.disableButtons(options.disabledButtons);

      // enable dropZone if available and configured
      if (options.dropZoneOptions) {
        if (this.$editor.dropzone) {
          if(!options.dropZoneOptions.init) {
            options.dropZoneOptions.init = function() {
              var caretPos = 0;
              this.on('drop', function(e) {
                  caretPos = textarea.prop('selectionStart');
                  });
              this.on('success', function(file, path) {
                  var text = textarea.val();
                  textarea.val(text.substring(0, caretPos) + '\n![description](' + path + ')\n' + text.substring(caretPos));
                  });
              this.on('error', function(file, error, xhr) {
                  console.log('Error:', error);
                  });
            };
          }
          this.$textarea.addClass('dropzone');
          this.$editor.dropzone(options.dropZoneOptions);
        } else {
          console.log('dropZoneOptions was configured, but DropZone was not detected.');
        }
      }

      // enable data-uris via drag and drop
      if (options.enableDropDataUri === true) {
        this.$editor.on('drop', function(e) {
          var caretPos = textarea.prop('selectionStart');
          e.stopPropagation();
          e.preventDefault();
          $.each(e.originalEvent.dataTransfer.files, function(index, file){
            var fileReader = new FileReader();
              fileReader.onload = (function(file) {
                 return function(e) {
                    var text = textarea.val();
                    textarea.val(text.substring(0, caretPos) + '\n<img src="'+ e.target.result  +'" />\n' + text.substring(caretPos) );
                 };
              })(file);
            fileReader.readAsDataURL(file);
          });
        });
      }

      // Trigger the onShow hook
      options.onShow(this);

      return this;
    },
    parseContent: function(val) {
      var content;

      // parse with supported markdown parser
      val = val || this.$textarea.val();

      if (this.$options.parser) {
        content = this.$options.parser(val);
      } else if (typeof markdown == 'object') {
        content = markdown.toHTML(val);
      } else if (typeof marked == 'function') {
        content = marked(val);
      } else {
        content = val;
      }

      return content;
    },
    showPreview: function() {
      var options = this.$options,
        container = this.$textarea,
        afterContainer = container.next(),
        replacementContainer = $('<div/>', {
          'class': 'md-preview',
          'data-provider': 'markdown-preview'
        }),
        content,
        callbackContent;

      if (this.$isPreview === true) {
        // Avoid sequenced element creation on missused scenario
        // @see https://github.com/toopay/bootstrap-markdown/issues/170
        return this;
      }

      // Give flag that tell the editor enter preview mode
      this.$isPreview = true;
      // Disable all buttons
      this.disableButtons('all').enableButtons('cmdPreview');

      // Try to get the content from callback
      callbackContent = options.onPreview(this);
      // Set the content based from the callback content if string otherwise parse value from textarea
      content = typeof callbackContent == 'string' ? callbackContent : this.parseContent();

      // Build preview element
      replacementContainer.html(content);

      if (afterContainer && afterContainer.attr('class') == 'md-footer') {
        // If there is footer element, insert the preview container before it
        replacementContainer.insertBefore(afterContainer);
      } else {
        // Otherwise, just append it after textarea
        container.parent().append(replacementContainer);
      }

      // Set the preview element dimensions
      replacementContainer.css({
        width: container.outerWidth() + 'px',
        height: container.outerHeight() + 'px'
      });

      if (this.$options.resize) {
        replacementContainer.css('resize', this.$options.resize);
      }

      // Hide the last-active textarea
      container.hide();

      // Attach the editor instances
      replacementContainer.data('markdown', this);

      if (this.$element.is(':disabled') || this.$element.is('[readonly]')) {
        this.$editor.addClass('md-editor-disabled');
        this.disableButtons('all');
      }

      return this;
    },
    hidePreview: function() {
      // Give flag that tell the editor quit preview mode
      this.$isPreview = false;

      // Obtain the preview container
      var container = this.$editor.find('div[data-provider="markdown-preview"]');

      // Remove the preview container
      container.remove();

      // Enable all buttons
      this.enableButtons('all');
      // Disable configured disabled buttons
      this.disableButtons(this.$options.disabledButtons);

      // Back to the editor
      this.$textarea.show();
      this.__setListener();

      return this;
    },
    isDirty: function() {
      return this.$oldContent != this.getContent();
    },
    getContent: function() {
      return this.$textarea.val();
    },
    setContent: function(content) {
      this.$textarea.val(content);

      return this;
    },
    findSelection: function(chunk) {
      var content = this.getContent(),
        startChunkPosition;

      if (startChunkPosition = content.indexOf(chunk), startChunkPosition >= 0 && chunk.length > 0) {
        var oldSelection = this.getSelection(),
          selection;

        this.setSelection(startChunkPosition, startChunkPosition + chunk.length);
        selection = this.getSelection();

        this.setSelection(oldSelection.start, oldSelection.end);

        return selection;
      } else {
        return null;
      }
    },
    getSelection: function() {

      var e = this.$textarea[0];

      return (

        ('selectionStart' in e && function() {
          var l = e.selectionEnd - e.selectionStart;
          return {
            start: e.selectionStart,
            end: e.selectionEnd,
            length: l,
            text: e.value.substr(e.selectionStart, l)
          };
        }) ||

        /* browser not supported */
        function() {
          return null;
        }

      )();

    },
    setSelection: function(start, end) {

      var e = this.$textarea[0];

      return (

        ('selectionStart' in e && function() {
          e.selectionStart = start;
          e.selectionEnd = end;
          return;
        }) ||

        /* browser not supported */
        function() {
          return null;
        }

      )();

    },
    replaceSelection: function(text) {

      var e = this.$textarea[0];

      return (

        ('selectionStart' in e && function() {
          e.value = e.value.substr(0, e.selectionStart) + text + e.value.substr(e.selectionEnd, e.value.length);
          // Set cursor to the last replacement end
          e.selectionStart = e.value.length;
          return this;
        }) ||

        /* browser not supported */
        function() {
          e.value += text;
          return jQuery(e);
        }

      )();
    },
    getNextTab: function() {
      // Shift the nextTab
      if (this.$nextTab.length === 0) {
        return null;
      } else {
        var nextTab, tab = this.$nextTab.shift();

        if (typeof tab == 'function') {
          nextTab = tab();
        } else if (typeof tab == 'object' && tab.length > 0) {
          nextTab = tab;
        }

        return nextTab;
      }
    },
    setNextTab: function(start, end) {
      // Push new selection into nextTab collections
      if (typeof start == 'string') {
        var that = this;
        this.$nextTab.push(function() {
          return that.findSelection(start);
        });
      } else if (typeof start == 'number' && typeof end == 'number') {
        var oldSelection = this.getSelection();

        this.setSelection(start, end);
        this.$nextTab.push(this.getSelection());

        this.setSelection(oldSelection.start, oldSelection.end);
      }

      return;
    },
    __parseButtonNameParam: function(names) {
      return typeof names == 'string' ?
        names.split(' ') :
        names;

    },
    enableButtons: function(name) {
      var buttons = this.__parseButtonNameParam(name),
        that = this;

      $.each(buttons, function(i, v) {
        that.__alterButtons(buttons[i], function(el) {
          el.removeAttr('disabled');
        });
      });

      return this;
    },
    disableButtons: function(name) {
      var buttons = this.__parseButtonNameParam(name),
        that = this;

      $.each(buttons, function(i, v) {
        that.__alterButtons(buttons[i], function(el) {
          el.attr('disabled', 'disabled');
        });
      });

      return this;
    },
    hideButtons: function(name) {
      var buttons = this.__parseButtonNameParam(name),
        that = this;

      $.each(buttons, function(i, v) {
        that.__alterButtons(buttons[i], function(el) {
          el.addClass('hidden');
        });
      });

      return this;
    },
    showButtons: function(name) {
      var buttons = this.__parseButtonNameParam(name),
        that = this;

      $.each(buttons, function(i, v) {
        that.__alterButtons(buttons[i], function(el) {
          el.removeClass('hidden');
        });
      });

      return this;
    },
    eventSupported: function(eventName) {
      var isSupported = eventName in this.$element;
      if (!isSupported) {
        this.$element.setAttribute(eventName, 'return;');
        isSupported = typeof this.$element[eventName] === 'function';
      }
      return isSupported;
    },
    keyup: function(e) {
      var blocked = false;
      switch (e.keyCode) {
        case 40: // down arrow
        case 38: // up arrow
        case 16: // shift
        case 17: // ctrl
        case 18: // alt
          break;

        case 9: // tab
          var nextTab;
          if (nextTab = this.getNextTab(), nextTab !== null) {
            // Get the nextTab if exists
            var that = this;
            setTimeout(function() {
              that.setSelection(nextTab.start, nextTab.end);
            }, 500);

            blocked = true;
          } else {
            // The next tab memory contains nothing...
            // check the cursor position to determine tab action
            var cursor = this.getSelection();

            if (cursor.start == cursor.end && cursor.end == this.getContent().length) {
              // The cursor already reach the end of the content
              blocked = false;
            } else {
              // Put the cursor to the end
              this.setSelection(this.getContent().length, this.getContent().length);

              blocked = true;
            }
          }

          break;

        case 13: // enter
          blocked = false;
          break;
        case 27: // escape
          if (this.$isFullscreen) this.setFullscreen(false);
          blocked = false;
          break;

        default:
          blocked = false;
      }

      if (blocked) {
        e.stopPropagation();
        e.preventDefault();
      }

      this.$options.onChange(this);
    },
    change: function(e) {
      this.$options.onChange(this);
      return this;
    },
    select: function(e) {
      this.$options.onSelect(this);
      return this;
    },
    focus: function(e) {
      var options = this.$options,
        isHideable = options.hideable,
        editor = this.$editor;

      editor.addClass('active');

      // Blur other markdown(s)
      $(document).find('.md-editor').each(function() {
        if ($(this).attr('id') !== editor.attr('id')) {
          var attachedMarkdown;

          if (attachedMarkdown = $(this).find('textarea').data('markdown'),
            attachedMarkdown === null) {
            attachedMarkdown = $(this).find('div[data-provider="markdown-preview"]').data('markdown');
          }

          if (attachedMarkdown) {
            attachedMarkdown.blur();
          }
        }
      });

      // Trigger the onFocus hook
      options.onFocus(this);

      return this;
    },
    blur: function(e) {
      var options = this.$options,
        isHideable = options.hideable,
        editor = this.$editor,
        editable = this.$editable;

      if (editor.hasClass('active') || this.$element.parent().length === 0) {
        editor.removeClass('active');

        if (isHideable) {
          // Check for editable elements
          if (editable.el !== null) {
            // Build the original element
            var oldElement = $('<' + editable.type + '/>'),
              content = this.getContent(),
              currentContent = this.parseContent(content);

            $(editable.attrKeys).each(function(k, v) {
              oldElement.attr(editable.attrKeys[k], editable.attrValues[k]);
            });

            // Get the editor content
            oldElement.html(currentContent);

            editor.replaceWith(oldElement);
          } else {
            editor.hide();
          }
        }

        // Trigger the onBlur hook
        options.onBlur(this);
      }

      return this;
    }

  };

  /* MARKDOWN PLUGIN DEFINITION
   * ========================== */

  var old = $.fn.markdown;

  $.fn.markdown = function(option) {
    return this.each(function() {
      var $this = $(this),
        data = $this.data('markdown'),
        options = typeof option == 'object' && option;
      if (!data)
        $this.data('markdown', (data = new Markdown(this, options)));
    });
  };

  $.fn.markdown.messages = {};

  $.fn.markdown.defaults = {
    /* Editor Properties */
    autofocus: false,
    hideable: false,
    savable: false,
    width: 'inherit',
    height: 'inherit',
    resize: 'none',
    iconlibrary: 'glyph',
    language: 'en',
    initialstate: 'editor',
    parser: null,
    dropZoneOptions: null,
    enableDropDataUri: false,

    /* Buttons Properties */
    buttons: [
      [{
        name: 'groupFont',
        data: [{
          name: 'cmdBold',
          hotkey: 'Ctrl+B',
          title: 'Bold',
          icon: {
            glyph: 'glyphicon glyphicon-bold',
            fa: 'fa fa-bold',
            'fa-3': 'icon-bold',
            octicons: 'octicon octicon-bold'
          },
          callback: function(e) {
            // Give/remove ** surround the selection
            var chunk, cursor, selected = e.getSelection(),
              content = e.getContent();

            if (selected.length === 0) {
              // Give extra word
              chunk = e.__localize('strong text');
            } else {
              chunk = selected.text;
            }

            // transform selection and set the cursor into chunked text
            if (content.substr(selected.start - 2, 2) === '**' &&
              content.substr(selected.end, 2) === '**') {
              e.setSelection(selected.start - 2, selected.end + 2);
              e.replaceSelection(chunk);
              cursor = selected.start - 2;
            } else {
              e.replaceSelection('**' + chunk + '**');
              cursor = selected.start + 2;
            }

            // Set the cursor
            e.setSelection(cursor, cursor + chunk.length);
          }
        }, {
          name: 'cmdItalic',
          title: 'Italic',
          hotkey: 'Ctrl+I',
          icon: {
            glyph: 'glyphicon glyphicon-italic',
            fa: 'fa fa-italic',
            'fa-3': 'icon-italic',
            octicons: 'octicon octicon-italic'
          },
          callback: function(e) {
            // Give/remove * surround the selection
            var chunk, cursor, selected = e.getSelection(),
              content = e.getContent();

            if (selected.length === 0) {
              // Give extra word
              chunk = e.__localize('emphasized text');
            } else {
              chunk = selected.text;
            }

            // transform selection and set the cursor into chunked text
            if (content.substr(selected.start - 1, 1) === '_' &&
              content.substr(selected.end, 1) === '_') {
              e.setSelection(selected.start - 1, selected.end + 1);
              e.replaceSelection(chunk);
              cursor = selected.start - 1;
            } else {
              e.replaceSelection('_' + chunk + '_');
              cursor = selected.start + 1;
            }

            // Set the cursor
            e.setSelection(cursor, cursor + chunk.length);
          }
        }, {
          name: 'cmdHeading',
          title: 'Heading',
          hotkey: 'Ctrl+H',
          icon: {
            glyph: 'glyphicon glyphicon-header',
            fa: 'fa fa-header',
            'fa-3': 'icon-font',
            octicons: 'octicon octicon-text-size'
          },
          callback: function(e) {
            // Append/remove ### surround the selection
            var chunk, cursor, selected = e.getSelection(),
              content = e.getContent(),
              pointer, prevChar;

            if (selected.length === 0) {
              // Give extra word
              chunk = e.__localize('heading text');
            } else {
              chunk = selected.text + '\n';
            }

            // transform selection and set the cursor into chunked text
            if ((pointer = 4, content.substr(selected.start - pointer, pointer) === '### ') ||
              (pointer = 3, content.substr(selected.start - pointer, pointer) === '###')) {
              e.setSelection(selected.start - pointer, selected.end);
              e.replaceSelection(chunk);
              cursor = selected.start - pointer;
            } else if (selected.start > 0 && (prevChar = content.substr(selected.start - 1, 1), !!prevChar && prevChar != '\n')) {
              e.replaceSelection('\n\n### ' + chunk);
              cursor = selected.start + 6;
            } else {
              // Empty string before element
              e.replaceSelection('### ' + chunk);
              cursor = selected.start + 4;
            }

            // Set the cursor
            e.setSelection(cursor, cursor + chunk.length);
          }
        }]
      }, {
        name: 'groupLink',
        data: [{
          name: 'cmdUrl',
          title: 'URL/Link',
          hotkey: 'Ctrl+L',
          icon: {
            glyph: 'glyphicon glyphicon-link',
            fa: 'fa fa-link',
            'fa-3': 'icon-link',
            octicons: 'octicon octicon-link'
          },
          callback: function(e) {
            // Give [] surround the selection and prepend the link
            var chunk, cursor, selected = e.getSelection(),
              content = e.getContent(),
              link;

            if (selected.length === 0) {
              // Give extra word
              chunk = e.__localize('enter link description here');
            } else {
              chunk = selected.text;
            }

            link = prompt(e.__localize('Insert Hyperlink'), 'http://');

            var urlRegex = new RegExp('^((http|https)://|(mailto:)|(//))[a-z0-9]', 'i');
            if (link !== null && link !== '' && link !== 'http://' && urlRegex.test(link)) {
              var sanitizedLink = $('<div>' + link + '</div>').text();

              // transform selection and set the cursor into chunked text
              e.replaceSelection('[' + chunk + '](' + sanitizedLink + ')');
              cursor = selected.start + 1;

              // Set the cursor
              e.setSelection(cursor, cursor + chunk.length);
            }
          }
        }, {
          name: 'cmdImage',
          title: 'Image',
          hotkey: 'Ctrl+G',
          icon: {
            glyph: 'glyphicon glyphicon-picture',
            fa: 'fa fa-picture-o',
            'fa-3': 'icon-picture',
            octicons: 'octicon octicon-file-media'
          },
          callback: function(e) {
            // Give ![] surround the selection and prepend the image link
            var chunk, cursor, selected = e.getSelection(),
              content = e.getContent(),
              link;

            if (selected.length === 0) {
              // Give extra word
              chunk = e.__localize('enter image description here');
            } else {
              chunk = selected.text;
            }

            link = prompt(e.__localize('Insert Image Hyperlink'), 'http://');

            var urlRegex = new RegExp('^((http|https)://|(//))[a-z0-9]', 'i');
            if (link !== null && link !== '' && link !== 'http://' && urlRegex.test(link)) {
              var sanitizedLink = $('<div>' + link + '</div>').text();

              // transform selection and set the cursor into chunked text
              e.replaceSelection('![' + chunk + '](' + sanitizedLink + ' "' + e.__localize('enter image title here') + '")');
              cursor = selected.start + 2;

              // Set the next tab
              e.setNextTab(e.__localize('enter image title here'));

              // Set the cursor
              e.setSelection(cursor, cursor + chunk.length);
            }
          }
        }]
      }, {
        name: 'groupMisc',
        data: [{
          name: 'cmdList',
          hotkey: 'Ctrl+U',
          title: 'Unordered List',
          icon: {
            glyph: 'glyphicon glyphicon-list',
            fa: 'fa fa-list',
            'fa-3': 'icon-list-ul',
            octicons: 'octicon octicon-list-unordered'
          },
          callback: function(e) {
            // Prepend/Give - surround the selection
            var chunk, cursor, selected = e.getSelection(),
              content = e.getContent();

            // transform selection and set the cursor into chunked text
            if (selected.length === 0) {
              // Give extra word
              chunk = e.__localize('list text here');

              e.replaceSelection('- ' + chunk);
              // Set the cursor
              cursor = selected.start + 2;
            } else {
              if (selected.text.indexOf('\n') < 0) {
                chunk = selected.text;

                e.replaceSelection('- ' + chunk);

                // Set the cursor
                cursor = selected.start + 2;
              } else {
                var list = [];

                list = selected.text.split('\n');
                chunk = list[0];

                $.each(list, function(k, v) {
                  list[k] = '- ' + v;
                });

                e.replaceSelection('\n\n' + list.join('\n'));

                // Set the cursor
                cursor = selected.start + 4;
              }
            }

            // Set the cursor
            e.setSelection(cursor, cursor + chunk.length);
          }
        }, {
          name: 'cmdListO',
          hotkey: 'Ctrl+O',
          title: 'Ordered List',
          icon: {
            glyph: 'glyphicon glyphicon-th-list',
            fa: 'fa fa-list-ol',
            'fa-3': 'icon-list-ol',
            octicons: 'octicon octicon-list-ordered'
          },
          callback: function(e) {

            // Prepend/Give - surround the selection
            var chunk, cursor, selected = e.getSelection(),
              content = e.getContent();

            // transform selection and set the cursor into chunked text
            if (selected.length === 0) {
              // Give extra word
              chunk = e.__localize('list text here');
              e.replaceSelection('1. ' + chunk);
              // Set the cursor
              cursor = selected.start + 3;
            } else {
              if (selected.text.indexOf('\n') < 0) {
                chunk = selected.text;

                e.replaceSelection('1. ' + chunk);

                // Set the cursor
                cursor = selected.start + 3;
              } else {
                var list = [];

                list = selected.text.split('\n');
                chunk = list[0];

                $.each(list, function(k, v) {
                  list[k] = '1. ' + v;
                });

                e.replaceSelection('\n\n' + list.join('\n'));

                // Set the cursor
                cursor = selected.start + 5;
              }
            }

            // Set the cursor
            e.setSelection(cursor, cursor + chunk.length);
          }
        }, {
          name: 'cmdCode',
          hotkey: 'Ctrl+K',
          title: 'Code',
          icon: {
            glyph: 'glyphicon glyphicon-console',
            fa: 'fa fa-code',
            'fa-3': 'icon-code',
            octicons: 'octicon octicon-code'
          },
          callback: function(e) {
            // Give/remove ** surround the selection
            var chunk, cursor, selected = e.getSelection(),
              content = e.getContent();

            if (selected.length === 0) {
              // Give extra word
              chunk = e.__localize('code text here');
            } else {
              chunk = selected.text;
            }

            // transform selection and set the cursor into chunked text
            if (content.substr(selected.start - 4, 4) === '```\n' &&
              content.substr(selected.end, 4) === '\n```') {
              e.setSelection(selected.start - 4, selected.end + 4);
              e.replaceSelection(chunk);
              cursor = selected.start - 4;
            } else if (content.substr(selected.start - 1, 1) === '`' &&
              content.substr(selected.end, 1) === '`') {
              e.setSelection(selected.start - 1, selected.end + 1);
              e.replaceSelection(chunk);
              cursor = selected.start - 1;
            } else if (content.indexOf('\n') > -1) {
              e.replaceSelection('```\n' + chunk + '\n```');
              cursor = selected.start + 4;
            } else {
              e.replaceSelection('`' + chunk + '`');
              cursor = selected.start + 1;
            }

            // Set the cursor
            e.setSelection(cursor, cursor + chunk.length);
          }
        }, {
          name: 'cmdQuote',
          hotkey: 'Ctrl+Q',
          title: 'Quote',
          icon: {
            glyph: 'glyphicon glyphicon-comment',
            fa: 'fa fa-quote-left',
            'fa-3': 'icon-quote-left',
            octicons: 'octicon octicon-quote'
          },
          callback: function(e) {
            // Prepend/Give - surround the selection
            var chunk, cursor, selected = e.getSelection(),
              content = e.getContent();

            // transform selection and set the cursor into chunked text
            if (selected.length === 0) {
              // Give extra word
              chunk = e.__localize('quote here');

              e.replaceSelection('> ' + chunk);

              // Set the cursor
              cursor = selected.start + 2;
            } else {
              if (selected.text.indexOf('\n') < 0) {
                chunk = selected.text;

                e.replaceSelection('> ' + chunk);

                // Set the cursor
                cursor = selected.start + 2;
              } else {
                var list = [];

                list = selected.text.split('\n');
                chunk = list[0];

                $.each(list, function(k, v) {
                  list[k] = '> ' + v;
                });

                e.replaceSelection('\n\n' + list.join('\n'));

                // Set the cursor
                cursor = selected.start + 4;
              }
            }

            // Set the cursor
            e.setSelection(cursor, cursor + chunk.length);
          }
        }]
      }, {
        name: 'groupUtil',
        data: [{
          name: 'cmdPreview',
          toggle: true,
          hotkey: 'Ctrl+P',
          title: 'Preview',
          btnText: 'Preview',
          btnClass: 'btn btn-primary btn-sm',
          icon: {
            glyph: 'glyphicon glyphicon-search',
            fa: 'fa fa-search',
            'fa-3': 'icon-search',
            octicons: 'octicon octicon-search'
          },
          callback: function(e) {
            // Check the preview mode and toggle based on this flag
            var isPreview = e.$isPreview,
              content;

            if (isPreview === false) {
              // Give flag that tell the editor enter preview mode
              e.showPreview();
            } else {
              e.hidePreview();
            }
          }
        }]
      }]
    ],
    additionalButtons: [], // Place to hook more buttons by code
    reorderButtonGroups: [],
    hiddenButtons: [], // Default hidden buttons
    disabledButtons: [], // Default disabled buttons
    footer: '',
    fullscreen: {
      enable: true,
      icons: {
        fullscreenOn: {
          fa: 'fa fa-expand',
          glyph: 'glyphicon glyphicon-fullscreen',
          'fa-3': 'icon-resize-full',
          octicons: 'octicon octicon-link-external'
        },
        fullscreenOff: {
          fa: 'fa fa-compress',
          glyph: 'glyphicon glyphicon-fullscreen',
          'fa-3': 'icon-resize-small',
          octicons: 'octicon octicon-browser'
        }
      }
    },

    /* Events hook */
    onShow: function(e) {},
    onPreview: function(e) {},
    onSave: function(e) {},
    onBlur: function(e) {},
    onFocus: function(e) {},
    onChange: function(e) {},
    onFullscreen: function(e) {},
    onFullscreenExit: function(e) {},
    onSelect: function(e) {}
  };

  $.fn.markdown.Constructor = Markdown;


  /* MARKDOWN NO CONFLICT
   * ==================== */

  $.fn.markdown.noConflict = function() {
    $.fn.markdown = old;
    return this;
  };

  /* MARKDOWN GLOBAL FUNCTION & DATA-API
   * ==================================== */
  var initMarkdown = function(el) {
    var $this = el;

    if ($this.data('markdown')) {
      $this.data('markdown').showEditor();
      return;
    }

    $this.markdown();
  };

  var blurNonFocused = function(e) {
    var $activeElement = $(document.activeElement);

    // Blur event
    $(document).find('.md-editor').each(function() {
      var $this = $(this),
        focused = $activeElement.closest('.md-editor')[0] === this,
        attachedMarkdown = $this.find('textarea').data('markdown') ||
        $this.find('div[data-provider="markdown-preview"]').data('markdown');

      if (attachedMarkdown && !focused) {
        attachedMarkdown.blur();
      }
    });
  };

  $(document)
    .on('click.markdown.data-api', '[data-provide="markdown-editable"]', function(e) {
      initMarkdown($(this));
      e.preventDefault();
    })
    .on('click focusin', function(e) {
      blurNonFocused(e);
    })
    .ready(function() {
      $('textarea[data-provide="markdown"]').each(function() {
        initMarkdown($(this));
      });
    });

}));

$(document).ready(function () {
    //var navbar = $('#navbar');
    //var menu = $('#menu-xs');

    //var lastScrollTop = 0;
    //$(document).on( 'scroll', function(){
        //var currentScrollTop = $(document).scrollTop();

        //var isScrollTop = (currentScrollTop - lastScrollTop) < 0;

        //if(isScrollTop) {
            //navbar.show();
        //} else {
        //    if (currentScrollTop > 100) {
        //        navbar.fadeOut();
        //    } else {
                //navbar.show();
            //}
        //}
        //lastScrollTop = currentScrollTop;
    //});


    //
    //$toggle = $('#navbar-toggle');
    //$toggle.click(function() {
    //    $isOpen = $(this).attr('aria-expanded');
    //
    //    if($isOpen) {
    //        menu.css('background-color', '#ffd346');
    //        navbar.css('background-color', '#ffd346');
    //    } else {
    //        menu.css('background-color', 'transparent');
    //
    //        var currentScrollTop = $(document).scrollTop();
    //        console.log(currentScrollTop);
    //        if( currentScrollTop <= 100 ) {
    //            navbar.css('background-color', 'transparent');
    //        }
    //    }
    //});

    var sky = $('.sky');
    var skyHeight = $(document).height();
    sky.css('height', skyHeight - 150);

    toastr.options = {
        "positionClass": "toast-top-center"
    };

    //$('.md-header').hide();
});

function search() {
    var searchForm = $('#searchForm');
    searchForm.submit();
}



var QRCode;!function(){function t(t){this.mode=l.MODE_8BIT_BYTE,this.data=t,this.parsedData=[];for(var e=0,r=this.data.length;r>e;e++){var i=[],n=this.data.charCodeAt(e);n>65536?(i[0]=240|(1835008&n)>>>18,i[1]=128|(258048&n)>>>12,i[2]=128|(4032&n)>>>6,i[3]=128|63&n):n>2048?(i[0]=224|(61440&n)>>>12,i[1]=128|(4032&n)>>>6,i[2]=128|63&n):n>128?(i[0]=192|(1984&n)>>>6,i[1]=128|63&n):i[0]=n,this.parsedData.push(i)}this.parsedData=Array.prototype.concat.apply([],this.parsedData),this.parsedData.length!=this.data.length&&(this.parsedData.unshift(191),this.parsedData.unshift(187),this.parsedData.unshift(239))}function e(t,e){this.typeNumber=t,this.errorCorrectLevel=e,this.modules=null,this.moduleCount=0,this.dataCache=null,this.dataList=[]}function r(t,e){if(void 0==t.length)throw new Error(t.length+"/"+e);for(var r=0;r<t.length&&0==t[r];)r++;this.num=new Array(t.length-r+e);for(var i=0;i<t.length-r;i++)this.num[i]=t[i+r]}function i(t,e){this.totalCount=t,this.dataCount=e}function n(){this.buffer=[],this.length=0}function o(){return"undefined"!=typeof CanvasRenderingContext2D}function a(){var t=!1,e=navigator.userAgent;if(/android/i.test(e)){t=!0;var r=e.toString().match(/android ([0-9]\.[0-9])/i);r&&r[1]&&(t=parseFloat(r[1]))}return t}function s(t,e){for(var r=1,i=h(t),n=0,o=p.length;o>=n;n++){var a=0;switch(e){case u.L:a=p[n][0];break;case u.M:a=p[n][1];break;case u.Q:a=p[n][2];break;case u.H:a=p[n][3]}if(a>=i)break;r++}if(r>p.length)throw new Error("Too long data");return r}function h(t){var e=encodeURI(t).toString().replace(/\%[0-9a-fA-F]{2}/g,"a");return e.length+(e.length!=t?3:0)}t.prototype={getLength:function(t){return this.parsedData.length},write:function(t){for(var e=0,r=this.parsedData.length;r>e;e++)t.put(this.parsedData[e],8)}},e.prototype={addData:function(e){var r=new t(e);this.dataList.push(r),this.dataCache=null},isDark:function(t,e){if(0>t||this.moduleCount<=t||0>e||this.moduleCount<=e)throw new Error(t+","+e);return this.modules[t][e]},getModuleCount:function(){return this.moduleCount},make:function(){this.makeImpl(!1,this.getBestMaskPattern())},makeImpl:function(t,r){this.moduleCount=4*this.typeNumber+17,this.modules=new Array(this.moduleCount);for(var i=0;i<this.moduleCount;i++){this.modules[i]=new Array(this.moduleCount);for(var n=0;n<this.moduleCount;n++)this.modules[i][n]=null}this.setupPositionProbePattern(0,0),this.setupPositionProbePattern(this.moduleCount-7,0),this.setupPositionProbePattern(0,this.moduleCount-7),this.setupPositionAdjustPattern(),this.setupTimingPattern(),this.setupTypeInfo(t,r),this.typeNumber>=7&&this.setupTypeNumber(t),null==this.dataCache&&(this.dataCache=e.createData(this.typeNumber,this.errorCorrectLevel,this.dataList)),this.mapData(this.dataCache,r)},setupPositionProbePattern:function(t,e){for(var r=-1;7>=r;r++)if(!(-1>=t+r||this.moduleCount<=t+r))for(var i=-1;7>=i;i++)-1>=e+i||this.moduleCount<=e+i||(r>=0&&6>=r&&(0==i||6==i)||i>=0&&6>=i&&(0==r||6==r)||r>=2&&4>=r&&i>=2&&4>=i?this.modules[t+r][e+i]=!0:this.modules[t+r][e+i]=!1)},getBestMaskPattern:function(){for(var t=0,e=0,r=0;8>r;r++){this.makeImpl(!0,r);var i=f.getLostPoint(this);(0==r||t>i)&&(t=i,e=r)}return e},createMovieClip:function(t,e,r){var i=t.createEmptyMovieClip(e,r),n=1;this.make();for(var o=0;o<this.modules.length;o++)for(var a=o*n,s=0;s<this.modules[o].length;s++){var h=s*n,l=this.modules[o][s];l&&(i.beginFill(0,100),i.moveTo(h,a),i.lineTo(h+n,a),i.lineTo(h+n,a+n),i.lineTo(h,a+n),i.endFill())}return i},setupTimingPattern:function(){for(var t=8;t<this.moduleCount-8;t++)null==this.modules[t][6]&&(this.modules[t][6]=t%2==0);for(var e=8;e<this.moduleCount-8;e++)null==this.modules[6][e]&&(this.modules[6][e]=e%2==0)},setupPositionAdjustPattern:function(){for(var t=f.getPatternPosition(this.typeNumber),e=0;e<t.length;e++)for(var r=0;r<t.length;r++){var i=t[e],n=t[r];if(null==this.modules[i][n])for(var o=-2;2>=o;o++)for(var a=-2;2>=a;a++)-2==o||2==o||-2==a||2==a||0==o&&0==a?this.modules[i+o][n+a]=!0:this.modules[i+o][n+a]=!1}},setupTypeNumber:function(t){for(var e=f.getBCHTypeNumber(this.typeNumber),r=0;18>r;r++){var i=!t&&1==(e>>r&1);this.modules[Math.floor(r/3)][r%3+this.moduleCount-8-3]=i}for(var r=0;18>r;r++){var i=!t&&1==(e>>r&1);this.modules[r%3+this.moduleCount-8-3][Math.floor(r/3)]=i}},setupTypeInfo:function(t,e){for(var r=this.errorCorrectLevel<<3|e,i=f.getBCHTypeInfo(r),n=0;15>n;n++){var o=!t&&1==(i>>n&1);6>n?this.modules[n][8]=o:8>n?this.modules[n+1][8]=o:this.modules[this.moduleCount-15+n][8]=o}for(var n=0;15>n;n++){var o=!t&&1==(i>>n&1);8>n?this.modules[8][this.moduleCount-n-1]=o:9>n?this.modules[8][15-n-1+1]=o:this.modules[8][15-n-1]=o}this.modules[this.moduleCount-8][8]=!t},mapData:function(t,e){for(var r=-1,i=this.moduleCount-1,n=7,o=0,a=this.moduleCount-1;a>0;a-=2)for(6==a&&a--;;){for(var s=0;2>s;s++)if(null==this.modules[i][a-s]){var h=!1;o<t.length&&(h=1==(t[o]>>>n&1));var l=f.getMask(e,i,a-s);l&&(h=!h),this.modules[i][a-s]=h,n--,-1==n&&(o++,n=7)}if(i+=r,0>i||this.moduleCount<=i){i-=r,r=-r;break}}}},e.PAD0=236,e.PAD1=17,e.createData=function(t,r,o){for(var a=i.getRSBlocks(t,r),s=new n,h=0;h<o.length;h++){var l=o[h];s.put(l.mode,4),s.put(l.getLength(),f.getLengthInBits(l.mode,t)),l.write(s)}for(var u=0,h=0;h<a.length;h++)u+=a[h].dataCount;if(s.getLengthInBits()>8*u)throw new Error("code length overflow. ("+s.getLengthInBits()+">"+8*u+")");for(s.getLengthInBits()+4<=8*u&&s.put(0,4);s.getLengthInBits()%8!=0;)s.putBit(!1);for(;;){if(s.getLengthInBits()>=8*u)break;if(s.put(e.PAD0,8),s.getLengthInBits()>=8*u)break;s.put(e.PAD1,8)}return e.createBytes(s,a)},e.createBytes=function(t,e){for(var i=0,n=0,o=0,a=new Array(e.length),s=new Array(e.length),h=0;h<e.length;h++){var l=e[h].dataCount,u=e[h].totalCount-l;n=Math.max(n,l),o=Math.max(o,u),a[h]=new Array(l);for(var c=0;c<a[h].length;c++)a[h][c]=255&t.buffer[c+i];i+=l;var d=f.getErrorCorrectPolynomial(u),g=new r(a[h],d.getLength()-1),p=g.mod(d);s[h]=new Array(d.getLength()-1);for(var c=0;c<s[h].length;c++){var m=c+p.getLength()-s[h].length;s[h][c]=m>=0?p.get(m):0}}for(var v=0,c=0;c<e.length;c++)v+=e[c].totalCount;for(var _=new Array(v),w=0,c=0;n>c;c++)for(var h=0;h<e.length;h++)c<a[h].length&&(_[w++]=a[h][c]);for(var c=0;o>c;c++)for(var h=0;h<e.length;h++)c<s[h].length&&(_[w++]=s[h][c]);return _};for(var l={MODE_NUMBER:1,MODE_ALPHA_NUM:2,MODE_8BIT_BYTE:4,MODE_KANJI:8},u={L:1,M:0,Q:3,H:2},c={PATTERN000:0,PATTERN001:1,PATTERN010:2,PATTERN011:3,PATTERN100:4,PATTERN101:5,PATTERN110:6,PATTERN111:7},f={PATTERN_POSITION_TABLE:[[],[6,18],[6,22],[6,26],[6,30],[6,34],[6,22,38],[6,24,42],[6,26,46],[6,28,50],[6,30,54],[6,32,58],[6,34,62],[6,26,46,66],[6,26,48,70],[6,26,50,74],[6,30,54,78],[6,30,56,82],[6,30,58,86],[6,34,62,90],[6,28,50,72,94],[6,26,50,74,98],[6,30,54,78,102],[6,28,54,80,106],[6,32,58,84,110],[6,30,58,86,114],[6,34,62,90,118],[6,26,50,74,98,122],[6,30,54,78,102,126],[6,26,52,78,104,130],[6,30,56,82,108,134],[6,34,60,86,112,138],[6,30,58,86,114,142],[6,34,62,90,118,146],[6,30,54,78,102,126,150],[6,24,50,76,102,128,154],[6,28,54,80,106,132,158],[6,32,58,84,110,136,162],[6,26,54,82,110,138,166],[6,30,58,86,114,142,170]],G15:1335,G18:7973,G15_MASK:21522,getBCHTypeInfo:function(t){for(var e=t<<10;f.getBCHDigit(e)-f.getBCHDigit(f.G15)>=0;)e^=f.G15<<f.getBCHDigit(e)-f.getBCHDigit(f.G15);return(t<<10|e)^f.G15_MASK},getBCHTypeNumber:function(t){for(var e=t<<12;f.getBCHDigit(e)-f.getBCHDigit(f.G18)>=0;)e^=f.G18<<f.getBCHDigit(e)-f.getBCHDigit(f.G18);return t<<12|e},getBCHDigit:function(t){for(var e=0;0!=t;)e++,t>>>=1;return e},getPatternPosition:function(t){return f.PATTERN_POSITION_TABLE[t-1]},getMask:function(t,e,r){switch(t){case c.PATTERN000:return(e+r)%2==0;case c.PATTERN001:return e%2==0;case c.PATTERN010:return r%3==0;case c.PATTERN011:return(e+r)%3==0;case c.PATTERN100:return(Math.floor(e/2)+Math.floor(r/3))%2==0;case c.PATTERN101:return e*r%2+e*r%3==0;case c.PATTERN110:return(e*r%2+e*r%3)%2==0;case c.PATTERN111:return(e*r%3+(e+r)%2)%2==0;default:throw new Error("bad maskPattern:"+t)}},getErrorCorrectPolynomial:function(t){for(var e=new r([1],0),i=0;t>i;i++)e=e.multiply(new r([1,d.gexp(i)],0));return e},getLengthInBits:function(t,e){if(e>=1&&10>e)switch(t){case l.MODE_NUMBER:return 10;case l.MODE_ALPHA_NUM:return 9;case l.MODE_8BIT_BYTE:return 8;case l.MODE_KANJI:return 8;default:throw new Error("mode:"+t)}else if(27>e)switch(t){case l.MODE_NUMBER:return 12;case l.MODE_ALPHA_NUM:return 11;case l.MODE_8BIT_BYTE:return 16;case l.MODE_KANJI:return 10;default:throw new Error("mode:"+t)}else{if(!(41>e))throw new Error("type:"+e);switch(t){case l.MODE_NUMBER:return 14;case l.MODE_ALPHA_NUM:return 13;case l.MODE_8BIT_BYTE:return 16;case l.MODE_KANJI:return 12;default:throw new Error("mode:"+t)}}},getLostPoint:function(t){for(var e=t.getModuleCount(),r=0,i=0;e>i;i++)for(var n=0;e>n;n++){for(var o=0,a=t.isDark(i,n),s=-1;1>=s;s++)if(!(0>i+s||i+s>=e))for(var h=-1;1>=h;h++)0>n+h||n+h>=e||(0!=s||0!=h)&&a==t.isDark(i+s,n+h)&&o++;o>5&&(r+=3+o-5)}for(var i=0;e-1>i;i++)for(var n=0;e-1>n;n++){var l=0;t.isDark(i,n)&&l++,t.isDark(i+1,n)&&l++,t.isDark(i,n+1)&&l++,t.isDark(i+1,n+1)&&l++,(0==l||4==l)&&(r+=3)}for(var i=0;e>i;i++)for(var n=0;e-6>n;n++)t.isDark(i,n)&&!t.isDark(i,n+1)&&t.isDark(i,n+2)&&t.isDark(i,n+3)&&t.isDark(i,n+4)&&!t.isDark(i,n+5)&&t.isDark(i,n+6)&&(r+=40);for(var n=0;e>n;n++)for(var i=0;e-6>i;i++)t.isDark(i,n)&&!t.isDark(i+1,n)&&t.isDark(i+2,n)&&t.isDark(i+3,n)&&t.isDark(i+4,n)&&!t.isDark(i+5,n)&&t.isDark(i+6,n)&&(r+=40);for(var u=0,n=0;e>n;n++)for(var i=0;e>i;i++)t.isDark(i,n)&&u++;var c=Math.abs(100*u/e/e-50)/5;return r+=10*c}},d={glog:function(t){if(1>t)throw new Error("glog("+t+")");return d.LOG_TABLE[t]},gexp:function(t){for(;0>t;)t+=255;for(;t>=256;)t-=255;return d.EXP_TABLE[t]},EXP_TABLE:new Array(256),LOG_TABLE:new Array(256)},g=0;8>g;g++)d.EXP_TABLE[g]=1<<g;for(var g=8;256>g;g++)d.EXP_TABLE[g]=d.EXP_TABLE[g-4]^d.EXP_TABLE[g-5]^d.EXP_TABLE[g-6]^d.EXP_TABLE[g-8];for(var g=0;255>g;g++)d.LOG_TABLE[d.EXP_TABLE[g]]=g;r.prototype={get:function(t){return this.num[t]},getLength:function(){return this.num.length},multiply:function(t){for(var e=new Array(this.getLength()+t.getLength()-1),i=0;i<this.getLength();i++)for(var n=0;n<t.getLength();n++)e[i+n]^=d.gexp(d.glog(this.get(i))+d.glog(t.get(n)));return new r(e,0)},mod:function(t){if(this.getLength()-t.getLength()<0)return this;for(var e=d.glog(this.get(0))-d.glog(t.get(0)),i=new Array(this.getLength()),n=0;n<this.getLength();n++)i[n]=this.get(n);for(var n=0;n<t.getLength();n++)i[n]^=d.gexp(d.glog(t.get(n))+e);return new r(i,0).mod(t)}},i.RS_BLOCK_TABLE=[[1,26,19],[1,26,16],[1,26,13],[1,26,9],[1,44,34],[1,44,28],[1,44,22],[1,44,16],[1,70,55],[1,70,44],[2,35,17],[2,35,13],[1,100,80],[2,50,32],[2,50,24],[4,25,9],[1,134,108],[2,67,43],[2,33,15,2,34,16],[2,33,11,2,34,12],[2,86,68],[4,43,27],[4,43,19],[4,43,15],[2,98,78],[4,49,31],[2,32,14,4,33,15],[4,39,13,1,40,14],[2,121,97],[2,60,38,2,61,39],[4,40,18,2,41,19],[4,40,14,2,41,15],[2,146,116],[3,58,36,2,59,37],[4,36,16,4,37,17],[4,36,12,4,37,13],[2,86,68,2,87,69],[4,69,43,1,70,44],[6,43,19,2,44,20],[6,43,15,2,44,16],[4,101,81],[1,80,50,4,81,51],[4,50,22,4,51,23],[3,36,12,8,37,13],[2,116,92,2,117,93],[6,58,36,2,59,37],[4,46,20,6,47,21],[7,42,14,4,43,15],[4,133,107],[8,59,37,1,60,38],[8,44,20,4,45,21],[12,33,11,4,34,12],[3,145,115,1,146,116],[4,64,40,5,65,41],[11,36,16,5,37,17],[11,36,12,5,37,13],[5,109,87,1,110,88],[5,65,41,5,66,42],[5,54,24,7,55,25],[11,36,12],[5,122,98,1,123,99],[7,73,45,3,74,46],[15,43,19,2,44,20],[3,45,15,13,46,16],[1,135,107,5,136,108],[10,74,46,1,75,47],[1,50,22,15,51,23],[2,42,14,17,43,15],[5,150,120,1,151,121],[9,69,43,4,70,44],[17,50,22,1,51,23],[2,42,14,19,43,15],[3,141,113,4,142,114],[3,70,44,11,71,45],[17,47,21,4,48,22],[9,39,13,16,40,14],[3,135,107,5,136,108],[3,67,41,13,68,42],[15,54,24,5,55,25],[15,43,15,10,44,16],[4,144,116,4,145,117],[17,68,42],[17,50,22,6,51,23],[19,46,16,6,47,17],[2,139,111,7,140,112],[17,74,46],[7,54,24,16,55,25],[34,37,13],[4,151,121,5,152,122],[4,75,47,14,76,48],[11,54,24,14,55,25],[16,45,15,14,46,16],[6,147,117,4,148,118],[6,73,45,14,74,46],[11,54,24,16,55,25],[30,46,16,2,47,17],[8,132,106,4,133,107],[8,75,47,13,76,48],[7,54,24,22,55,25],[22,45,15,13,46,16],[10,142,114,2,143,115],[19,74,46,4,75,47],[28,50,22,6,51,23],[33,46,16,4,47,17],[8,152,122,4,153,123],[22,73,45,3,74,46],[8,53,23,26,54,24],[12,45,15,28,46,16],[3,147,117,10,148,118],[3,73,45,23,74,46],[4,54,24,31,55,25],[11,45,15,31,46,16],[7,146,116,7,147,117],[21,73,45,7,74,46],[1,53,23,37,54,24],[19,45,15,26,46,16],[5,145,115,10,146,116],[19,75,47,10,76,48],[15,54,24,25,55,25],[23,45,15,25,46,16],[13,145,115,3,146,116],[2,74,46,29,75,47],[42,54,24,1,55,25],[23,45,15,28,46,16],[17,145,115],[10,74,46,23,75,47],[10,54,24,35,55,25],[19,45,15,35,46,16],[17,145,115,1,146,116],[14,74,46,21,75,47],[29,54,24,19,55,25],[11,45,15,46,46,16],[13,145,115,6,146,116],[14,74,46,23,75,47],[44,54,24,7,55,25],[59,46,16,1,47,17],[12,151,121,7,152,122],[12,75,47,26,76,48],[39,54,24,14,55,25],[22,45,15,41,46,16],[6,151,121,14,152,122],[6,75,47,34,76,48],[46,54,24,10,55,25],[2,45,15,64,46,16],[17,152,122,4,153,123],[29,74,46,14,75,47],[49,54,24,10,55,25],[24,45,15,46,46,16],[4,152,122,18,153,123],[13,74,46,32,75,47],[48,54,24,14,55,25],[42,45,15,32,46,16],[20,147,117,4,148,118],[40,75,47,7,76,48],[43,54,24,22,55,25],[10,45,15,67,46,16],[19,148,118,6,149,119],[18,75,47,31,76,48],[34,54,24,34,55,25],[20,45,15,61,46,16]],i.getRSBlocks=function(t,e){var r=i.getRsBlockTable(t,e);if(void 0==r)throw new Error("bad rs block @ typeNumber:"+t+"/errorCorrectLevel:"+e);for(var n=r.length/3,o=[],a=0;n>a;a++)for(var s=r[3*a+0],h=r[3*a+1],l=r[3*a+2],u=0;s>u;u++)o.push(new i(h,l));return o},i.getRsBlockTable=function(t,e){switch(e){case u.L:return i.RS_BLOCK_TABLE[4*(t-1)+0];case u.M:return i.RS_BLOCK_TABLE[4*(t-1)+1];case u.Q:return i.RS_BLOCK_TABLE[4*(t-1)+2];case u.H:return i.RS_BLOCK_TABLE[4*(t-1)+3];default:return}},n.prototype={get:function(t){var e=Math.floor(t/8);return 1==(this.buffer[e]>>>7-t%8&1)},put:function(t,e){for(var r=0;e>r;r++)this.putBit(1==(t>>>e-r-1&1))},getLengthInBits:function(){return this.length},putBit:function(t){var e=Math.floor(this.length/8);this.buffer.length<=e&&this.buffer.push(0),t&&(this.buffer[e]|=128>>>this.length%8),this.length++}};var p=[[17,14,11,7],[32,26,20,14],[53,42,32,24],[78,62,46,34],[106,84,60,44],[134,106,74,58],[154,122,86,64],[192,152,108,84],[230,180,130,98],[271,213,151,119],[321,251,177,137],[367,287,203,155],[425,331,241,177],[458,362,258,194],[520,412,292,220],[586,450,322,250],[644,504,364,280],[718,560,394,310],[792,624,442,338],[858,666,482,382],[929,711,509,403],[1003,779,565,439],[1091,857,611,461],[1171,911,661,511],[1273,997,715,535],[1367,1059,751,593],[1465,1125,805,625],[1528,1190,868,658],[1628,1264,908,698],[1732,1370,982,742],[1840,1452,1030,790],[1952,1538,1112,842],[2068,1628,1168,898],[2188,1722,1228,958],[2303,1809,1283,983],[2431,1911,1351,1051],[2563,1989,1423,1093],[2699,2099,1499,1139],[2809,2213,1579,1219],[2953,2331,1663,1273]],m=function(){var t=function(t,e){this._el=t,this._htOption=e};return t.prototype.draw=function(t){function e(t,e){var r=document.createElementNS("http://www.w3.org/2000/svg",t);for(var i in e)e.hasOwnProperty(i)&&r.setAttribute(i,e[i]);return r}var r=this._htOption,i=this._el,n=t.getModuleCount();Math.floor(r.width/n),Math.floor(r.height/n);this.clear();var o=e("svg",{viewBox:"0 0 "+String(n)+" "+String(n),width:"100%",height:"100%",fill:r.colorLight});o.setAttributeNS("http://www.w3.org/2000/xmlns/","xmlns:xlink","http://www.w3.org/1999/xlink"),i.appendChild(o),o.appendChild(e("rect",{fill:r.colorLight,width:"100%",height:"100%"})),o.appendChild(e("rect",{fill:r.colorDark,width:"1",height:"1",id:"template"}));for(var a=0;n>a;a++)for(var s=0;n>s;s++)if(t.isDark(a,s)){var h=e("use",{x:String(s),y:String(a)});h.setAttributeNS("http://www.w3.org/1999/xlink","href","#template"),o.appendChild(h)}},t.prototype.clear=function(){for(;this._el.hasChildNodes();)this._el.removeChild(this._el.lastChild)},t}(),v="svg"===document.documentElement.tagName.toLowerCase(),_=v?m:o()?function(){function t(){this._elImage.src=this._elCanvas.toDataURL("image/png"),this._elImage.style.display="block",this._elCanvas.style.display="none"}function e(t,e){var r=this;if(r._fFail=e,r._fSuccess=t,null===r._bSupportDataURI){var i=document.createElement("img"),n=function(){r._bSupportDataURI=!1,r._fFail&&r._fFail.call(r)},o=function(){r._bSupportDataURI=!0,r._fSuccess&&r._fSuccess.call(r)};return i.onabort=n,i.onerror=n,i.onload=o,void(i.src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg==")}r._bSupportDataURI===!0&&r._fSuccess?r._fSuccess.call(r):r._bSupportDataURI===!1&&r._fFail&&r._fFail.call(r)}if(this._android&&this._android<=2.1){var r=1/window.devicePixelRatio,i=CanvasRenderingContext2D.prototype.drawImage;CanvasRenderingContext2D.prototype.drawImage=function(t,e,n,o,a,s,h,l,u){if("nodeName"in t&&/img/i.test(t.nodeName))for(var c=arguments.length-1;c>=1;c--)arguments[c]=arguments[c]*r;else"undefined"==typeof l&&(arguments[1]*=r,arguments[2]*=r,arguments[3]*=r,arguments[4]*=r);i.apply(this,arguments)}}var n=function(t,e){this._bIsPainted=!1,this._android=a(),this._htOption=e,this._elCanvas=document.createElement("canvas"),this._elCanvas.width=e.width,this._elCanvas.height=e.height,t.appendChild(this._elCanvas),this._el=t,this._oContext=this._elCanvas.getContext("2d"),this._bIsPainted=!1,this._elImage=document.createElement("img"),this._elImage.alt="Scan me!",this._elImage.style.display="none",this._el.appendChild(this._elImage),this._bSupportDataURI=null};return n.prototype.draw=function(t){var e=this._elImage,r=this._oContext,i=this._htOption,n=t.getModuleCount(),o=i.width/n,a=i.height/n,s=Math.round(o),h=Math.round(a);e.style.display="none",this.clear();for(var l=0;n>l;l++)for(var u=0;n>u;u++){var c=t.isDark(l,u),f=u*o,d=l*a;r.strokeStyle=c?i.colorDark:i.colorLight,r.lineWidth=1,r.fillStyle=c?i.colorDark:i.colorLight,r.fillRect(f,d,o,a),r.strokeRect(Math.floor(f)+.5,Math.floor(d)+.5,s,h),r.strokeRect(Math.ceil(f)-.5,Math.ceil(d)-.5,s,h)}this._bIsPainted=!0},n.prototype.makeImage=function(){this._bIsPainted&&e.call(this,t)},n.prototype.isPainted=function(){return this._bIsPainted},n.prototype.clear=function(){this._oContext.clearRect(0,0,this._elCanvas.width,this._elCanvas.height),this._bIsPainted=!1},n.prototype.round=function(t){return t?Math.floor(1e3*t)/1e3:t},n}():function(){var t=function(t,e){this._el=t,this._htOption=e};return t.prototype.draw=function(t){for(var e=this._htOption,r=this._el,i=t.getModuleCount(),n=Math.floor(e.width/i),o=Math.floor(e.height/i),a=['<table style="border:0;border-collapse:collapse;">'],s=0;i>s;s++){a.push("<tr>");for(var h=0;i>h;h++)a.push('<td style="border:0;border-collapse:collapse;padding:0;margin:0;width:'+n+"px;height:"+o+"px;background-color:"+(t.isDark(s,h)?e.colorDark:e.colorLight)+';"></td>');a.push("</tr>")}a.push("</table>"),r.innerHTML=a.join("");var l=r.childNodes[0],u=(e.width-l.offsetWidth)/2,c=(e.height-l.offsetHeight)/2;u>0&&c>0&&(l.style.margin=c+"px "+u+"px")},t.prototype.clear=function(){this._el.innerHTML=""},t}();QRCode=function(t,e){if(this._htOption={width:256,height:256,typeNumber:4,colorDark:"#000000",colorLight:"#ffffff",correctLevel:u.H},"string"==typeof e&&(e={text:e}),e)for(var r in e)this._htOption[r]=e[r];"string"==typeof t&&(t=document.getElementById(t)),this._htOption.useSVG&&(_=m),this._android=a(),this._el=t,this._oQRCode=null,this._oDrawing=new _(this._el,this._htOption),this._htOption.text&&this.makeCode(this._htOption.text)},QRCode.prototype.makeCode=function(t){this._oQRCode=new e(s(t,this._htOption.correctLevel),this._htOption.correctLevel),this._oQRCode.addData(t),this._oQRCode.make(),this._el.title=t,this._oDrawing.draw(this._oQRCode),this.makeImage()},QRCode.prototype.makeImage=function(){"function"==typeof this._oDrawing.makeImage&&(!this._android||this._android>=3)&&this._oDrawing.makeImage()},QRCode.prototype.clear=function(){this._oDrawing.clear()},QRCode.CorrectLevel=u}(),function(t,e,r){function i(t,e){var r=g({},b,e||{},p(t));u(t,"share-component social-share"),n(t,r),o(t,r),t.initialized=!0}function n(t,e){var r=a(e),i="prepend"==e.mode;v(i?r.reverse():r,function(r){var n=s(r,e),o=e.initialized?f(t,".icon-"+r):d('<a class="social-share-icon icon-'+r+'" target="_blank"></a>');return o.length?(o[0].href=n,void(e.initialized||(i?t.insertBefore(o[0],t.firstChild):t.appendChild(o[0])))):!0})}function o(t,e){var r=f(t,"icon-wechat","a");if(0===r.length)return!1;var i=d('<div class="wechat-qrcode"><h4>'+e.wechatQrcodeTitle+'</h4><div class="qrcode"></div><div class="help">'+e.wechatQrcodeHelper+"</div></div>"),n=f(i[0],"qrcode","div");r[0].appendChild(i[0]),new QRCode(n[0],{text:e.url,width:100,height:100})}function a(t){t.mobileSites.length||(t.mobileSites=t.sites);var e=(A?t.mobileSites:t.sites).slice(0),r=t.disabled;return"string"==typeof e&&(e=e.split(/\s*,\s*/)),"string"==typeof r&&(r=r.split(/\s*,\s*/)),E&&r.push("wechat"),r.length&&v(r,function(t){e.splice(m(t,e),1)}),e}function s(t,e){return e.summary=e.description,B[t].replace(/\{\{(\w)(\w*)\}\}/g,function(r,i,n){var o=t+i+n.toLowerCase();return n=(i+n).toLowerCase(),encodeURIComponent(e[o]||e[n]||"")})}function h(r){return(e.querySelectorAll||t.jQuery||t.Zepto||l).call(e,r)}function l(t){var r=[];return v(t.split(/\s*,\s*/),function(i){var n=i.match(/([#.])(\w+)/);if(null===n)throw Error("Supports only simple single #ID or .CLASS selector.");if(n[1]){var o=e.getElementById(n[2]);o&&r.push(o)}r=r.concat(f(t))}),r}function u(t,e){if(e&&"string"==typeof e){var r=(t.className+" "+e).split(/\s+/),i=" ";v(r,function(t){i.indexOf(" "+t+" ")<0&&(i+=t+" ")}),t.className=i.slice(1,-1)}}function c(t){return(e.getElementsByName(t)[0]||0).content}function f(t,e,r){if(t.getElementsByClassName)return t.getElementsByClassName(e);var i=[],n=t.getElementsByTagName(r||"*");return e=" "+e+" ",v(n,function(t){(" "+(t.className||"")+" ").indexOf(e)>=0&&i.push(t)}),i}function d(t){var r=e.createElement("div");return r.innerHTML=t,r.childNodes}function g(){var t=arguments;if(C)return C.apply(null,t);var e={};return v(t,function(t){v(t,function(t,r){e[r]=t})}),t[0]=e}function p(t){if(t.dataset)return t.dataset;var e={};return t.hasAttributes()?(v(t.attributes,function(t){var r=t.name;return 0!==r.indexOf("data-")?!0:(r=r.replace(/^data-/i,"").replace(/-(\w)/g,function(t,e){return e.toUpperCase()}),void(e[r]=t.value))}),e):{}}function m(t,e,r){var i;if(e){if(w)return w.call(e,t,r);for(i=e.length,r=r?0>r?Math.max(0,i+r):r:0;i>r;r++)if(r in e&&e[r]===t)return r}return-1}function v(t,e){var i=t.length;if(i===r){for(var n in t)if(t.hasOwnProperty(n)&&e.call(t[n],t[n],n)===!1)break}else for(var o=0;i>o&&e.call(t[o],t[o],o)!==!1;o++);}function _(r){var i="addEventListener",n=e[i]?"":"on";~e.readyState.indexOf("m")?r():"load DOMContentLoaded readystatechange".replace(/\w+/g,function(o,a){(a?e:t)[n?"attachEvent":i](n+o,function(){r&&(6>a||~e.readyState.indexOf("m"))&&(r(),r=0)},!1)})}var w=Array.prototype.indexOf,C=Object.assign,E=/MicroMessenger/i.test(navigator.userAgent),A=e.documentElement.clientWidth<=768,T=(e.images[0]||0).src||"",y=c("site")||c("Site")||e.title,L=c("title")||c("Title")||e.title,D=c("description")||c("Description")||"",b={url:location.href,origin:location.origin,source:y,title:L,description:D,image:T,weiboKey:"",wechatQrcodeTitle:"微信扫一扫：分享",wechatQrcodeHelper:"<p>微信里点“发现”，扫一下</p><p>二维码便可将本文分享至朋友圈。</p>",sites:["weibo","qq","wechat","tencent","douban","qzone","linkedin","diandian","facebook","twitter","google"],mobileSites:[],disabled:[],initialized:!1},B={qzone:"http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url={{URL}}&title={{TITLE}}&desc={{DESCRIPTION}}&summary={{SUMMARY}}&site={{SOURCE}}",qq:"http://connect.qq.com/widget/shareqq/index.html?url={{URL}}&title={{TITLE}}&source={{SOURCE}}&desc={{DESCRIPTION}}",tencent:"http://share.v.t.qq.com/index.php?c=share&a=index&title={{TITLE}}&url={{URL}}&pic={{IMAGE}}",weibo:"http://service.weibo.com/share/share.php?url={{URL}}&title={{TITLE}}&pic={{IMAGE}}&appkey={{WEIBOKEY}}",wechat:"javascript:",douban:"http://shuo.douban.com/!service/share?href={{URL}}&name={{TITLE}}&text={{DESCRIPTION}}&image={{IMAGE}}&starid=0&aid=0&style=11",diandian:"http://www.diandian.com/share?lo={{URL}}&ti={{TITLE}}&type=link",linkedin:"http://www.linkedin.com/shareArticle?mini=true&ro=true&title={{TITLE}}&url={{URL}}&summary={{SUMMARY}}&source={{SOURCE}}&armin=armin",facebook:"https://www.facebook.com/sharer/sharer.php?u={{URL}}",twitter:"https://twitter.com/intent/tweet?text={{TITLE}}&url={{URL}}&via={{ORIGIN}}",google:"https://plus.google.com/share?url={{URL}}"};t.socialShare=function(t,e){t="string"==typeof t?h(t):t,t.length===r&&(t=[t]),v(t,function(t){t.initialized||i(t,e)})},_(function(){socialShare(".social-share, .share-component")})}(window,document);
//# sourceMappingURL=app.js.map
=======
function search(){var t=$("#searchForm");t.submit()}!function(t){"use strict";function e(e){t(e).on("click"+p,this.toggle)}function n(t,e){t.hasClass("pull-center")&&t.css("margin-right",t.outerWidth()/-2),t.hasClass("pull-middle")&&t.css("margin-top",t.outerHeight()/-2-e.outerHeight()/2)}function i(e,n){if(a){n||(n=[a]);var i;a[0]!==n[0][0]?i=a:(i=n[n.length-1],i.parent().hasClass(h)&&(i=i.parent())),i.find("."+f).removeClass(f),i.hasClass(f)&&i.removeClass(f),i===a&&(a=null,t(c).remove())}}function o(t){for(var e,n=[t];!e||e.hasClass(u);)e=(e||t).parent(),e.hasClass(h)&&(e=e.parent()),e.children(s)&&n.unshift(e);return n}function r(e){var n=e.attr("data-target");n||(n=e.attr("href"),n=n&&/#[A-Za-z]/.test(n)&&n.replace(/.*(?=#[^\s]*$)/,""));var i=n&&t(n);return i&&i.length?i:e.parent()}var a,s='[data-toggle="dropdown"]',l=".disabled, :disabled",c=".dropdown-backdrop",h="dropdown-menu",u="dropdown-submenu",d=".bs.dropdown.data-api",p=".bs.dropdown",f="open",g="ontouchstart"in document.documentElement,m=e.prototype;m.toggle=function(e){var s=t(this);if(!s.is(l)){var d=r(s),p=d.hasClass(f),m=d.hasClass(u),v=m?o(d):null;if(i(e,v),!p){v||(v=[d]),!g||d.closest(".navbar-nav").length||v[0].find(c).length||t('<div class="'+c.substr(1)+'"/>').appendTo(v[0]).on("click",i);for(var w=0,y=v.length;y>w;w++)v[w].hasClass(f)||(v[w].addClass(f),n(v[w].children("."+h),v[w]));a=v[0]}return!1}},m.keydown=function(e){if(/(38|40|27)/.test(e.keyCode)){var n=t(this);if(e.preventDefault(),e.stopPropagation(),!n.is(".disabled, :disabled")){var i=r(n),o=i.hasClass("open");if(!o||o&&27==e.keyCode)return 27==e.which&&i.find(s).trigger("focus"),n.trigger("click");var a=" li:not(.divider):visible a",l="li:not(.divider):visible > input:not(disabled) ~ label",c=i.find(l+', [role="menu"]'+a+', [role="listbox"]'+a);if(c.length){var h=c.index(c.filter(":focus"));38==e.keyCode&&h>0&&h--,40==e.keyCode&&h<c.length-1&&h++,~h||(h=0),c.eq(h).trigger("focus")}}}},m.change=function(e){var n,i,o,r="";if(n=t(this).closest("."+h),i=n.parent().find("[data-label-placement]"),i&&i.length||(i=n.parent().find(s)),i&&i.length&&i.data("placeholder")!==!1){void 0==i.data("placeholder")&&i.data("placeholder",t.trim(i.text())),r=t.data(i[0],"placeholder"),o=n.find("li > input:checked"),o.length&&(r=[],o.each(function(){var e=t(this).parent().find("label").eq(0),n=e.find(".data-label");if(n.length){var i=t("<p></p>");i.append(n.clone()),e=i.html()}else e=e.html();e&&r.push(t.trim(e))}),r=r.length<4?r.join(", "):r.length+" selected");var a=i.find(".caret");i.html(r||"&nbsp;"),a.length&&i.append(" ")&&a.appendTo(i)}};var v=t.fn.dropdown;t.fn.dropdown=function(n){return this.each(function(){var i=t(this),o=i.data("bs.dropdown");o||i.data("bs.dropdown",o=new e(this)),"string"==typeof n&&o[n].call(i)})},t.fn.dropdown.Constructor=e,t.fn.dropdown.clearMenus=function(e){return t(c).remove(),t("."+f+" "+s).each(function(){var n=r(t(this)),i={relatedTarget:this};n.hasClass("open")&&(n.trigger(e=t.Event("hide"+p,i)),e.isDefaultPrevented()||n.removeClass("open").trigger("hidden"+p,i))}),this},t.fn.dropdown.noConflict=function(){return t.fn.dropdown=v,this},t(document).off(d).on("click"+d,i).on("click"+d,s,m.toggle).on("click"+d,'.dropdown-menu > li > input[type="checkbox"] ~ label, .dropdown-menu > li > input[type="checkbox"], .dropdown-menu.noclose > li',function(t){t.stopPropagation()}).on("change"+d,'.dropdown-menu > li > input[type="checkbox"], .dropdown-menu > li > input[type="radio"]',m.change).on("keydown"+d,s+', [role="menu"], [role="listbox"]',m.keydown)}(jQuery),$(document).ready(function(){fullscreenSupport()}),$(window).resize(function(){fullscreenSupport()});var fullscreenSupport=function(){var t=$(window).height()-80;$(window).width();$(".fullscreen").css("min-height",t)};+function(t){"use strict";function e(e){return this.each(function(){var i=t(this),o=i.data("bs.tooltip"),r="object"==typeof e&&e;!o&&/destroy|hide/.test(e)||(o||i.data("bs.tooltip",o=new n(this,r)),"string"==typeof e&&o[e]())})}var n=function(t,e){this.type=null,this.options=null,this.enabled=null,this.timeout=null,this.hoverState=null,this.$element=null,this.inState=null,this.init("tooltip",t,e)};n.VERSION="3.3.6",n.TRANSITION_DURATION=150,n.DEFAULTS={animation:!0,placement:"top",selector:!1,template:'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:!1,container:!1,viewport:{selector:"body",padding:0}},n.prototype.init=function(e,n,i){if(this.enabled=!0,this.type=e,this.$element=t(n),this.options=this.getOptions(i),this.$viewport=this.options.viewport&&t(t.isFunction(this.options.viewport)?this.options.viewport.call(this,this.$element):this.options.viewport.selector||this.options.viewport),this.inState={click:!1,hover:!1,focus:!1},this.$element[0]instanceof document.constructor&&!this.options.selector)throw new Error("`selector` option must be specified when initializing "+this.type+" on the window.document object!");for(var o=this.options.trigger.split(" "),r=o.length;r--;){var a=o[r];if("click"==a)this.$element.on("click."+this.type,this.options.selector,t.proxy(this.toggle,this));else if("manual"!=a){var s="hover"==a?"mouseenter":"focusin",l="hover"==a?"mouseleave":"focusout";this.$element.on(s+"."+this.type,this.options.selector,t.proxy(this.enter,this)),this.$element.on(l+"."+this.type,this.options.selector,t.proxy(this.leave,this))}}this.options.selector?this._options=t.extend({},this.options,{trigger:"manual",selector:""}):this.fixTitle()},n.prototype.getDefaults=function(){return n.DEFAULTS},n.prototype.getOptions=function(e){return e=t.extend({},this.getDefaults(),this.$element.data(),e),e.delay&&"number"==typeof e.delay&&(e.delay={show:e.delay,hide:e.delay}),e},n.prototype.getDelegateOptions=function(){var e={},n=this.getDefaults();return this._options&&t.each(this._options,function(t,i){n[t]!=i&&(e[t]=i)}),e},n.prototype.enter=function(e){var n=e instanceof this.constructor?e:t(e.currentTarget).data("bs."+this.type);return n||(n=new this.constructor(e.currentTarget,this.getDelegateOptions()),t(e.currentTarget).data("bs."+this.type,n)),e instanceof t.Event&&(n.inState["focusin"==e.type?"focus":"hover"]=!0),n.tip().hasClass("in")||"in"==n.hoverState?void(n.hoverState="in"):(clearTimeout(n.timeout),n.hoverState="in",n.options.delay&&n.options.delay.show?void(n.timeout=setTimeout(function(){"in"==n.hoverState&&n.show()},n.options.delay.show)):n.show())},n.prototype.isInStateTrue=function(){for(var t in this.inState)if(this.inState[t])return!0;return!1},n.prototype.leave=function(e){var n=e instanceof this.constructor?e:t(e.currentTarget).data("bs."+this.type);return n||(n=new this.constructor(e.currentTarget,this.getDelegateOptions()),t(e.currentTarget).data("bs."+this.type,n)),e instanceof t.Event&&(n.inState["focusout"==e.type?"focus":"hover"]=!1),n.isInStateTrue()?void 0:(clearTimeout(n.timeout),n.hoverState="out",n.options.delay&&n.options.delay.hide?void(n.timeout=setTimeout(function(){"out"==n.hoverState&&n.hide()},n.options.delay.hide)):n.hide())},n.prototype.show=function(){var e=t.Event("show.bs."+this.type);if(this.hasContent()&&this.enabled){this.$element.trigger(e);var i=t.contains(this.$element[0].ownerDocument.documentElement,this.$element[0]);if(e.isDefaultPrevented()||!i)return;var o=this,r=this.tip(),a=this.getUID(this.type);this.setContent(),r.attr("id",a),this.$element.attr("aria-describedby",a),this.options.animation&&r.addClass("fade");var s="function"==typeof this.options.placement?this.options.placement.call(this,r[0],this.$element[0]):this.options.placement,l=/\s?auto?\s?/i,c=l.test(s);c&&(s=s.replace(l,"")||"top"),r.detach().css({top:0,left:0,display:"block"}).addClass(s).data("bs."+this.type,this),this.options.container?r.appendTo(this.options.container):r.insertAfter(this.$element),this.$element.trigger("inserted.bs."+this.type);var h=this.getPosition(),u=r[0].offsetWidth,d=r[0].offsetHeight;if(c){var p=s,f=this.getPosition(this.$viewport);s="bottom"==s&&h.bottom+d>f.bottom?"top":"top"==s&&h.top-d<f.top?"bottom":"right"==s&&h.right+u>f.width?"left":"left"==s&&h.left-u<f.left?"right":s,r.removeClass(p).addClass(s)}var g=this.getCalculatedOffset(s,h,u,d);this.applyPlacement(g,s);var m=function(){var t=o.hoverState;o.$element.trigger("shown.bs."+o.type),o.hoverState=null,"out"==t&&o.leave(o)};t.support.transition&&this.$tip.hasClass("fade")?r.one("bsTransitionEnd",m).emulateTransitionEnd(n.TRANSITION_DURATION):m()}},n.prototype.applyPlacement=function(e,n){var i=this.tip(),o=i[0].offsetWidth,r=i[0].offsetHeight,a=parseInt(i.css("margin-top"),10),s=parseInt(i.css("margin-left"),10);isNaN(a)&&(a=0),isNaN(s)&&(s=0),e.top+=a,e.left+=s,t.offset.setOffset(i[0],t.extend({using:function(t){i.css({top:Math.round(t.top),left:Math.round(t.left)})}},e),0),i.addClass("in");var l=i[0].offsetWidth,c=i[0].offsetHeight;"top"==n&&c!=r&&(e.top=e.top+r-c);var h=this.getViewportAdjustedDelta(n,e,l,c);h.left?e.left+=h.left:e.top+=h.top;var u=/top|bottom/.test(n),d=u?2*h.left-o+l:2*h.top-r+c,p=u?"offsetWidth":"offsetHeight";i.offset(e),this.replaceArrow(d,i[0][p],u)},n.prototype.replaceArrow=function(t,e,n){this.arrow().css(n?"left":"top",50*(1-t/e)+"%").css(n?"top":"left","")},n.prototype.setContent=function(){var t=this.tip(),e=this.getTitle();t.find(".tooltip-inner")[this.options.html?"html":"text"](e),t.removeClass("fade in top bottom left right")},n.prototype.hide=function(e){function i(){"in"!=o.hoverState&&r.detach(),o.$element.removeAttr("aria-describedby").trigger("hidden.bs."+o.type),e&&e()}var o=this,r=t(this.$tip),a=t.Event("hide.bs."+this.type);return this.$element.trigger(a),a.isDefaultPrevented()?void 0:(r.removeClass("in"),t.support.transition&&r.hasClass("fade")?r.one("bsTransitionEnd",i).emulateTransitionEnd(n.TRANSITION_DURATION):i(),this.hoverState=null,this)},n.prototype.fixTitle=function(){var t=this.$element;(t.attr("title")||"string"!=typeof t.attr("data-original-title"))&&t.attr("data-original-title",t.attr("title")||"").attr("title","")},n.prototype.hasContent=function(){return this.getTitle()},n.prototype.getPosition=function(e){e=e||this.$element;var n=e[0],i="BODY"==n.tagName,o=n.getBoundingClientRect();null==o.width&&(o=t.extend({},o,{width:o.right-o.left,height:o.bottom-o.top}));var r=i?{top:0,left:0}:e.offset(),a={scroll:i?document.documentElement.scrollTop||document.body.scrollTop:e.scrollTop()},s=i?{width:t(window).width(),height:t(window).height()}:null;return t.extend({},o,a,s,r)},n.prototype.getCalculatedOffset=function(t,e,n,i){return"bottom"==t?{top:e.top+e.height,left:e.left+e.width/2-n/2}:"top"==t?{top:e.top-i,left:e.left+e.width/2-n/2}:"left"==t?{top:e.top+e.height/2-i/2,left:e.left-n}:{top:e.top+e.height/2-i/2,left:e.left+e.width}},n.prototype.getViewportAdjustedDelta=function(t,e,n,i){var o={top:0,left:0};if(!this.$viewport)return o;var r=this.options.viewport&&this.options.viewport.padding||0,a=this.getPosition(this.$viewport);if(/right|left/.test(t)){var s=e.top-r-a.scroll,l=e.top+r-a.scroll+i;s<a.top?o.top=a.top-s:l>a.top+a.height&&(o.top=a.top+a.height-l)}else{var c=e.left-r,h=e.left+r+n;c<a.left?o.left=a.left-c:h>a.right&&(o.left=a.left+a.width-h)}return o},n.prototype.getTitle=function(){var t,e=this.$element,n=this.options;return t=e.attr("data-original-title")||("function"==typeof n.title?n.title.call(e[0]):n.title)},n.prototype.getUID=function(t){do t+=~~(1e6*Math.random());while(document.getElementById(t));return t},n.prototype.tip=function(){if(!this.$tip&&(this.$tip=t(this.options.template),1!=this.$tip.length))throw new Error(this.type+" `template` option must consist of exactly 1 top-level element!");return this.$tip},n.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".tooltip-arrow")},n.prototype.enable=function(){this.enabled=!0},n.prototype.disable=function(){this.enabled=!1},n.prototype.toggleEnabled=function(){this.enabled=!this.enabled},n.prototype.toggle=function(e){var n=this;e&&(n=t(e.currentTarget).data("bs."+this.type),n||(n=new this.constructor(e.currentTarget,this.getDelegateOptions()),t(e.currentTarget).data("bs."+this.type,n))),e?(n.inState.click=!n.inState.click,n.isInStateTrue()?n.enter(n):n.leave(n)):n.tip().hasClass("in")?n.leave(n):n.enter(n)},n.prototype.destroy=function(){var t=this;clearTimeout(this.timeout),this.hide(function(){t.$element.off("."+t.type).removeData("bs."+t.type),t.$tip&&t.$tip.detach(),t.$tip=null,t.$arrow=null,t.$viewport=null})};var i=t.fn.tooltip;t.fn.tooltip=e,t.fn.tooltip.Constructor=n,t.fn.tooltip.noConflict=function(){return t.fn.tooltip=i,this}}(jQuery),function(t,e,n,i,o,r,a){t.GoogleAnalyticsObject=o,t[o]=t[o]||function(){(t[o].q=t[o].q||[]).push(arguments)},t[o].l=1*new Date,r=e.createElement(n),a=e.getElementsByTagName(n)[0],r.async=1,r.src=i,a.parentNode.insertBefore(r,a)}(window,document,"script","https://www.google-analytics.com/analytics.js","ga"),ga("create","UA-79348501-1","auto"),ga("send","pageview"),!function(t){t(["jquery"],function(t){return function(){function e(t,e,n){return f({type:C.error,iconClass:g().iconClasses.error,message:t,optionsOverride:n,title:e})}function n(e,n){return e||(e=g()),v=t("#"+e.containerId),v.length?v:(n&&(v=u(e)),v)}function i(t,e,n){return f({type:C.info,iconClass:g().iconClasses.info,message:t,optionsOverride:n,title:e})}function o(t){w=t}function r(t,e,n){return f({type:C.success,iconClass:g().iconClasses.success,message:t,optionsOverride:n,title:e})}function a(t,e,n){return f({type:C.warning,iconClass:g().iconClasses.warning,message:t,optionsOverride:n,title:e})}function s(t){var e=g();v||n(e),h(t,e)||c(e)}function l(e){var i=g();return v||n(i),e&&0===t(":focus",e).length?void m(e):void(v.children().length&&v.remove())}function c(e){for(var n=v.children(),i=n.length-1;i>=0;i--)h(t(n[i]),e)}function h(e,n){return e&&0===t(":focus",e).length?(e[n.hideMethod]({duration:n.hideDuration,easing:n.hideEasing,complete:function(){m(e)}}),!0):!1}function u(e){return v=t("<div/>").attr("id",e.containerId).addClass(e.positionClass).attr("aria-live","polite").attr("role","alert"),v.appendTo(t(e.target)),v}function d(){return{tapToDismiss:!0,toastClass:"toast",containerId:"toast-container",debug:!1,showMethod:"fadeIn",showDuration:300,showEasing:"swing",onShown:void 0,hideMethod:"fadeOut",hideDuration:1e3,hideEasing:"swing",onHidden:void 0,extendedTimeOut:1e3,iconClasses:{error:"toast-error",info:"toast-info",success:"toast-success",warning:"toast-warning"},iconClass:"toast-info",positionClass:"toast-top-right",timeOut:5e3,titleClass:"toast-title",messageClass:"toast-message",target:"body",closeHtml:'<button type="button">&times;</button>',newestOnTop:!0,preventDuplicates:!1,progressBar:!1}}function p(t){w&&w(t)}function f(e){function i(e){return!t(":focus",h).length||e?(clearTimeout(C.intervalId),h[s.hideMethod]({duration:s.hideDuration,easing:s.hideEasing,complete:function(){m(h),s.onHidden&&"hidden"!==_.state&&s.onHidden(),_.state="hidden",_.endTime=new Date,p(_)}})):void 0}function o(){(s.timeOut>0||s.extendedTimeOut>0)&&(c=setTimeout(i,s.extendedTimeOut),C.maxHideTime=parseFloat(s.extendedTimeOut),C.hideEta=(new Date).getTime()+C.maxHideTime)}function r(){clearTimeout(c),C.hideEta=0,h.stop(!0,!0)[s.showMethod]({duration:s.showDuration,easing:s.showEasing})}function a(){var t=(C.hideEta-(new Date).getTime())/C.maxHideTime*100;f.width(t+"%")}var s=g(),l=e.iconClass||s.iconClass;if("undefined"!=typeof e.optionsOverride&&(s=t.extend(s,e.optionsOverride),l=e.optionsOverride.iconClass||l),s.preventDuplicates){if(e.message===y)return;y=e.message}b++,v=n(s,!0);var c=null,h=t("<div/>"),u=t("<div/>"),d=t("<div/>"),f=t("<div/>"),w=t(s.closeHtml),C={intervalId:null,hideEta:null,maxHideTime:null},_={toastId:b,state:"visible",startTime:new Date,options:s,map:e};return e.iconClass&&h.addClass(s.toastClass).addClass(l),e.title&&(u.append(e.title).addClass(s.titleClass),h.append(u)),e.message&&(d.append(e.message).addClass(s.messageClass),h.append(d)),s.closeButton&&(w.addClass("toast-close-button").attr("role","button"),h.prepend(w)),s.progressBar&&(f.addClass("toast-progress"),h.prepend(f)),h.hide(),s.newestOnTop?v.prepend(h):v.append(h),h[s.showMethod]({duration:s.showDuration,easing:s.showEasing,complete:s.onShown}),s.timeOut>0&&(c=setTimeout(i,s.timeOut),C.maxHideTime=parseFloat(s.timeOut),C.hideEta=(new Date).getTime()+C.maxHideTime,s.progressBar&&(C.intervalId=setInterval(a,10))),h.hover(r,o),!s.onclick&&s.tapToDismiss&&h.click(i),s.closeButton&&w&&w.click(function(t){t.stopPropagation?t.stopPropagation():void 0!==t.cancelBubble&&t.cancelBubble!==!0&&(t.cancelBubble=!0),i(!0)}),s.onclick&&h.click(function(){s.onclick(),i()}),p(_),s.debug&&console&&void 0,h}function g(){return t.extend({},d(),_.options)}function m(t){v||(v=n()),t.is(":visible")||(t.remove(),t=null,0===v.children().length&&(v.remove(),y=void 0))}var v,w,y,b=0,C={error:"error",info:"info",success:"success",warning:"warning"},_={clear:s,remove:l,error:e,getContainer:n,info:i,options:{},subscribe:o,success:r,version:"2.1.0",warning:a};return _}()})}("function"==typeof define&&define.amd?define:function(t,e){"undefined"!=typeof module&&module.exports?module.exports=e(require("jquery")):window.toastr=e(window.jQuery)}),function(t){t.fn.goTop=function(e){t.fn.goTop.defaults={appear:200,scrolltime:800,src:"glyphicon glyphicon-chevron-up",width:45,place:"right",fadein:500,fadeout:500,opacity:.5,marginX:2,marginY:2};var n=t.extend({},t.fn.goTop.defaults,e);return this.each(function(){var e=t(this);e.html("<a id='goTopAnchor'><span id='goTopSpan' /></a>");var i=e.children("a"),o=i.children("span"),r={position:"fixed",display:"block",width:"'"+n.width+"px'","z-index":"9",bottom:n.marginY+"%"};r["left"===n.place?"left":"right"]=n.marginX+"%",e.css(r),i.css("opacity",n.opacity),o.addClass(n.src),o.css("font-size",n.width),o.hide(),t(function(){t(window).scroll(function(){t(this).scrollTop()>n.appear?o.fadeIn(n.fadein):o.fadeOut(n.fadeout)}),t(i).hover(function(){t(this).css("opacity","1.0"),t(this).css("cursor","pointer"),t(this).css("color","#273041")},function(){t(this).css("opacity",n.opacity)}),t(i).click(function(){return t("body,html").animate({scrollTop:0},n.scrolltime),!1})})})}}(jQuery),function(t){"function"==typeof define&&define.amd?define(["jquery"],t):t("object"==typeof exports?require("jquery"):jQuery)}(function(t){"use strict";var e=function(e,n){var i=["autofocus","savable","hideable","width","height","resize","iconlibrary","language","footer","fullscreen","hiddenButtons","disabledButtons"];t.each(i,function(i,o){"undefined"!=typeof t(e).data(o)&&(n="object"==typeof n?n:{},n[o]=t(e).data(o))}),this.$ns="bootstrap-markdown",this.$element=t(e),this.$editable={el:null,type:null,attrKeys:[],attrValues:[],content:null},this.$options=t.extend(!0,{},t.fn.markdown.defaults,n,this.$element.data("options")),this.$oldContent=null,this.$isPreview=!1,this.$isFullscreen=!1,this.$editor=null,this.$textarea=null,this.$handler=[],this.$callback=[],this.$nextTab=[],this.showEditor()};e.prototype={constructor:e,__alterButtons:function(e,n){var i=this.$handler,o="all"==e,r=this;t.each(i,function(t,i){var a=!0;a=o?!1:i.indexOf(e)<0,a===!1&&n(r.$editor.find('button[data-handler="'+i+'"]'))})},__buildButtons:function(e,n){var i,o=this.$ns,r=this.$handler,a=this.$callback;for(i=0;i<e.length;i++){var s,l=e[i];for(s=0;s<l.length;s++){var c,h=l[s].data,u=t("<div/>",{"class":"btn-group"});for(c=0;c<h.length;c++){var d,p,f=h[c],g=o+"-"+f.name,m=this.__getIcon(f.icon),v=f.btnText?f.btnText:"",w=f.btnClass?f.btnClass:"btn",y=f.tabIndex?f.tabIndex:"-1",b="undefined"!=typeof f.hotkey?f.hotkey:"",C="undefined"!=typeof jQuery.hotkeys&&""!==b?" ("+b+")":"";d=t("<button></button>"),d.text(" "+this.__localize(v)).addClass("btn-default btn-sm").addClass(w),w.match(/btn\-(primary|success|info|warning|danger|link)/)&&d.removeClass("btn-default"),d.attr({type:"button",title:this.__localize(f.title)+C,tabindex:y,"data-provider":o,"data-handler":g,"data-hotkey":b}),f.toggle===!0&&d.attr("data-toggle","button"),p=t("<span/>"),p.addClass(m),p.prependTo(d),u.append(d),r.push(g),a.push(f.callback)}n.append(u)}}return n},__setListener:function(){var e="undefined"!=typeof this.$textarea.attr("rows"),n=this.$textarea.val().split("\n").length>5?this.$textarea.val().split("\n").length:"5",i=e?this.$textarea.attr("rows"):n;this.$textarea.attr("rows",i),this.$options.resize&&this.$textarea.css("resize",this.$options.resize),this.$textarea.on({focus:t.proxy(this.focus,this),keyup:t.proxy(this.keyup,this),change:t.proxy(this.change,this),select:t.proxy(this.select,this)}),this.eventSupported("keydown")&&this.$textarea.on("keydown",t.proxy(this.keydown,this)),this.eventSupported("keypress")&&this.$textarea.on("keypress",t.proxy(this.keypress,this)),this.$textarea.data("markdown",this)},__handle:function(e){var n=t(e.currentTarget),i=this.$handler,o=this.$callback,r=n.attr("data-handler"),a=i.indexOf(r),s=o[a];t(e.currentTarget).focus(),s(this),this.change(this),r.indexOf("cmdSave")<0&&this.$textarea.focus(),e.preventDefault()},__localize:function(e){var n=t.fn.markdown.messages,i=this.$options.language;return"undefined"!=typeof n&&"undefined"!=typeof n[i]&&"undefined"!=typeof n[i][e]?n[i][e]:e},__getIcon:function(t){return"object"==typeof t?t[this.$options.iconlibrary]:t},setFullscreen:function(e){var n=this.$editor,i=this.$textarea;e===!0?(n.addClass("md-fullscreen-mode"),t("body").addClass("md-nooverflow"),this.$options.onFullscreen(this)):(n.removeClass("md-fullscreen-mode"),t("body").removeClass("md-nooverflow"),this.$options.onFullscreenExit(this),this.$isPreview===!0&&this.hidePreview().showPreview()),this.$isFullscreen=e,i.focus()},showEditor:function(){var e,n=this,i=this.$ns,o=this.$element,r=(o.css("height"),o.css("width"),this.$editable),a=this.$handler,s=this.$callback,l=this.$options,c=t("<div/>",{"class":"md-editor",click:function(){n.focus()}});if(null===this.$editor){var h=t("<div/>",{"class":"md-header btn-toolbar"}),u=[];if(l.buttons.length>0&&(u=u.concat(l.buttons[0])),l.additionalButtons.length>0&&t.each(l.additionalButtons[0],function(e,n){var i=t.grep(u,function(t,e){return t.name===n.name});i.length>0?i[0].data=i[0].data.concat(n.data):u.push(l.additionalButtons[0][e])}),l.reorderButtonGroups.length>0&&(u=u.filter(function(t){return l.reorderButtonGroups.indexOf(t.name)>-1}).sort(function(t,e){return l.reorderButtonGroups.indexOf(t.name)<l.reorderButtonGroups.indexOf(e.name)?-1:l.reorderButtonGroups.indexOf(t.name)>l.reorderButtonGroups.indexOf(e.name)?1:0})),u.length>0&&(h=this.__buildButtons([u],h)),l.fullscreen.enable&&h.append('<div class="md-controls"><a class="md-control md-control-fullscreen" href="#"><span class="'+this.__getIcon(l.fullscreen.icons.fullscreenOn)+'"></span></a></div>').on("click",".md-control-fullscreen",function(t){t.preventDefault(),n.setFullscreen(!0)}),c.append(h),o.is("textarea"))o.before(c),e=o,e.addClass("md-input"),c.append(e);else{var d="function"==typeof toMarkdown?toMarkdown(o.html()):o.html(),p=t.trim(d);e=t("<textarea/>",{"class":"md-input",val:p}),c.append(e),r.el=o,r.type=o.prop("tagName").toLowerCase(),r.content=o.html(),t(o[0].attributes).each(function(){r.attrKeys.push(this.nodeName),r.attrValues.push(this.nodeValue)}),o.replaceWith(c)}var f=t("<div/>",{"class":"md-footer"}),g=!1,m="";if(l.savable){g=!0;var v="cmdSave";a.push(v),s.push(l.onSave),f.append('<button class="btn btn-success" data-provider="'+i+'" data-handler="'+v+'"><i class="icon icon-white icon-ok"></i> '+this.__localize("Save")+"</button>")}if(m="function"==typeof l.footer?l.footer(this):l.footer,""!==t.trim(m)&&(g=!0,f.append(m)),g&&c.append(f),l.width&&"inherit"!==l.width&&(jQuery.isNumeric(l.width)?(c.css("display","table"),e.css("width",l.width+"px")):c.addClass(l.width)),l.height&&"inherit"!==l.height)if(jQuery.isNumeric(l.height)){var w=l.height;h&&(w=Math.max(0,w-h.outerHeight())),f&&(w=Math.max(0,w-f.outerHeight())),e.css("height",w+"px")}else c.addClass(l.height);this.$editor=c,this.$textarea=e,this.$editable=r,this.$oldContent=this.getContent(),this.__setListener(),this.$editor.attr("id",(new Date).getTime()),this.$editor.on("click",'[data-provider="bootstrap-markdown"]',t.proxy(this.__handle,this)),(this.$element.is(":disabled")||this.$element.is("[readonly]"))&&(this.$editor.addClass("md-editor-disabled"),this.disableButtons("all")),this.eventSupported("keydown")&&"object"==typeof jQuery.hotkeys&&h.find('[data-provider="bootstrap-markdown"]').each(function(){var n=t(this),i=n.attr("data-hotkey");""!==i.toLowerCase()&&e.bind("keydown",i,function(){return n.trigger("click"),!1})}),"preview"===l.initialstate?this.showPreview():"fullscreen"===l.initialstate&&l.fullscreen.enable&&this.setFullscreen(!0)}else this.$editor.show();return l.autofocus&&(this.$textarea.focus(),this.$editor.addClass("active")),l.fullscreen.enable&&l.fullscreen!==!1&&(this.$editor.append('<div class="md-fullscreen-controls"><a href="#" class="exit-fullscreen" title="Exit fullscreen"><span class="'+this.__getIcon(l.fullscreen.icons.fullscreenOff)+'"></span></a></div>'),this.$editor.on("click",".exit-fullscreen",function(t){t.preventDefault(),n.setFullscreen(!1)})),this.hideButtons(l.hiddenButtons),this.disableButtons(l.disabledButtons),l.dropZoneOptions&&this.$editor.dropzone&&(l.dropZoneOptions.init||(l.dropZoneOptions.init=function(){var t=0;this.on("drop",function(n){t=e.prop("selectionStart")}),this.on("success",function(n,i){var o=e.val();e.val(o.substring(0,t)+"\n![description]("+i+")\n"+o.substring(t))}),this.on("error",function(t,e,n){})}),this.$textarea.addClass("dropzone"),this.$editor.dropzone(l.dropZoneOptions)),l.enableDropDataUri===!0&&this.$editor.on("drop",function(n){var i=e.prop("selectionStart");n.stopPropagation(),n.preventDefault(),t.each(n.originalEvent.dataTransfer.files,function(t,n){var o=new FileReader;o.onload=function(t){return function(t){var n=e.val();e.val(n.substring(0,i)+'\n<img src="'+t.target.result+'" />\n'+n.substring(i))}}(n),o.readAsDataURL(n)})}),l.onShow(this),this},parseContent:function(t){var e;return t=t||this.$textarea.val(),e=this.$options.parser?this.$options.parser(t):"object"==typeof markdown?markdown.toHTML(t):"function"==typeof marked?marked(t):t},showPreview:function(){var e,n,i=this.$options,o=this.$textarea,r=o.next(),a=t("<div/>",{"class":"md-preview","data-provider":"markdown-preview"});return this.$isPreview===!0?this:(this.$isPreview=!0,this.disableButtons("all").enableButtons("cmdPreview"),n=i.onPreview(this),e="string"==typeof n?n:this.parseContent(),a.html(e),r&&"md-footer"==r.attr("class")?a.insertBefore(r):o.parent().append(a),a.css({width:o.outerWidth()+"px",height:o.outerHeight()+"px"}),this.$options.resize&&a.css("resize",this.$options.resize),o.hide(),a.data("markdown",this),(this.$element.is(":disabled")||this.$element.is("[readonly]"))&&(this.$editor.addClass("md-editor-disabled"),this.disableButtons("all")),this)},hidePreview:function(){this.$isPreview=!1;var t=this.$editor.find('div[data-provider="markdown-preview"]');return t.remove(),this.enableButtons("all"),this.disableButtons(this.$options.disabledButtons),this.$textarea.show(),this.__setListener(),this},isDirty:function(){return this.$oldContent!=this.getContent()},getContent:function(){return this.$textarea.val()},setContent:function(t){return this.$textarea.val(t),this},findSelection:function(t){var e,n=this.getContent();if(e=n.indexOf(t),e>=0&&t.length>0){var i,o=this.getSelection();return this.setSelection(e,e+t.length),i=this.getSelection(),this.setSelection(o.start,o.end),i}return null},getSelection:function(){var t=this.$textarea[0];return("selectionStart"in t&&function(){var e=t.selectionEnd-t.selectionStart;return{start:t.selectionStart,end:t.selectionEnd,length:e,text:t.value.substr(t.selectionStart,e)}}||function(){return null})()},setSelection:function(t,e){var n=this.$textarea[0];return("selectionStart"in n&&function(){n.selectionStart=t,n.selectionEnd=e}||function(){return null})()},replaceSelection:function(t){var e=this.$textarea[0];return("selectionStart"in e&&function(){return e.value=e.value.substr(0,e.selectionStart)+t+e.value.substr(e.selectionEnd,e.value.length),e.selectionStart=e.value.length,this}||function(){return e.value+=t,jQuery(e)})()},getNextTab:function(){if(0===this.$nextTab.length)return null;var t,e=this.$nextTab.shift();return"function"==typeof e?t=e():"object"==typeof e&&e.length>0&&(t=e),t},setNextTab:function(t,e){if("string"==typeof t){var n=this;this.$nextTab.push(function(){return n.findSelection(t)})}else if("number"==typeof t&&"number"==typeof e){var i=this.getSelection();this.setSelection(t,e),this.$nextTab.push(this.getSelection()),this.setSelection(i.start,i.end)}},__parseButtonNameParam:function(t){return"string"==typeof t?t.split(" "):t},enableButtons:function(e){var n=this.__parseButtonNameParam(e),i=this;return t.each(n,function(t,e){i.__alterButtons(n[t],function(t){t.removeAttr("disabled")})}),this},disableButtons:function(e){var n=this.__parseButtonNameParam(e),i=this;return t.each(n,function(t,e){i.__alterButtons(n[t],function(t){t.attr("disabled","disabled")})}),this},hideButtons:function(e){var n=this.__parseButtonNameParam(e),i=this;return t.each(n,function(t,e){i.__alterButtons(n[t],function(t){t.addClass("hidden")})}),this},showButtons:function(e){var n=this.__parseButtonNameParam(e),i=this;return t.each(n,function(t,e){i.__alterButtons(n[t],function(t){t.removeClass("hidden")})}),this},eventSupported:function(t){var e=t in this.$element;return e||(this.$element.setAttribute(t,"return;"),e="function"==typeof this.$element[t]),e},keyup:function(t){var e=!1;switch(t.keyCode){case 40:case 38:case 16:case 17:case 18:break;case 9:var n;if(n=this.getNextTab(),null!==n){var i=this;setTimeout(function(){i.setSelection(n.start,n.end)},500),e=!0}else{var o=this.getSelection();o.start==o.end&&o.end==this.getContent().length?e=!1:(this.setSelection(this.getContent().length,this.getContent().length),e=!0)}break;case 13:e=!1;break;case 27:this.$isFullscreen&&this.setFullscreen(!1),e=!1;break;default:e=!1}e&&(t.stopPropagation(),t.preventDefault()),this.$options.onChange(this)},change:function(t){return this.$options.onChange(this),this},select:function(t){return this.$options.onSelect(this),this},focus:function(e){var n=this.$options,i=(n.hideable,this.$editor);return i.addClass("active"),t(document).find(".md-editor").each(function(){if(t(this).attr("id")!==i.attr("id")){var e;e=t(this).find("textarea").data("markdown"),null===e&&(e=t(this).find('div[data-provider="markdown-preview"]').data("markdown")),e&&e.blur()}}),n.onFocus(this),this},blur:function(e){var n=this.$options,i=n.hideable,o=this.$editor,r=this.$editable;if(o.hasClass("active")||0===this.$element.parent().length){if(o.removeClass("active"),i)if(null!==r.el){var a=t("<"+r.type+"/>"),s=this.getContent(),l=this.parseContent(s);t(r.attrKeys).each(function(t,e){a.attr(r.attrKeys[t],r.attrValues[t])}),a.html(l),o.replaceWith(a)}else o.hide();n.onBlur(this)}return this}};var n=t.fn.markdown;t.fn.markdown=function(n){return this.each(function(){var i=t(this),o=i.data("markdown"),r="object"==typeof n&&n;o||i.data("markdown",o=new e(this,r))})},t.fn.markdown.messages={},t.fn.markdown.defaults={autofocus:!1,hideable:!1,savable:!1,width:"inherit",height:"inherit",resize:"none",iconlibrary:"glyph",language:"en",initialstate:"editor",parser:null,dropZoneOptions:null,enableDropDataUri:!1,buttons:[[{name:"groupFont",data:[{name:"cmdBold",hotkey:"Ctrl+B",title:"Bold",icon:{glyph:"glyphicon glyphicon-bold",fa:"fa fa-bold","fa-3":"icon-bold",octicons:"octicon octicon-bold"},callback:function(t){var e,n,i=t.getSelection(),o=t.getContent();e=0===i.length?t.__localize("strong text"):i.text,"**"===o.substr(i.start-2,2)&&"**"===o.substr(i.end,2)?(t.setSelection(i.start-2,i.end+2),t.replaceSelection(e),n=i.start-2):(t.replaceSelection("**"+e+"**"),n=i.start+2),t.setSelection(n,n+e.length);
}},{name:"cmdItalic",title:"Italic",hotkey:"Ctrl+I",icon:{glyph:"glyphicon glyphicon-italic",fa:"fa fa-italic","fa-3":"icon-italic",octicons:"octicon octicon-italic"},callback:function(t){var e,n,i=t.getSelection(),o=t.getContent();e=0===i.length?t.__localize("emphasized text"):i.text,"_"===o.substr(i.start-1,1)&&"_"===o.substr(i.end,1)?(t.setSelection(i.start-1,i.end+1),t.replaceSelection(e),n=i.start-1):(t.replaceSelection("_"+e+"_"),n=i.start+1),t.setSelection(n,n+e.length)}},{name:"cmdHeading",title:"Heading",hotkey:"Ctrl+H",icon:{glyph:"glyphicon glyphicon-header",fa:"fa fa-header","fa-3":"icon-font",octicons:"octicon octicon-text-size"},callback:function(t){var e,n,i,o,r=t.getSelection(),a=t.getContent();e=0===r.length?t.__localize("heading text"):r.text+"\n",i=4,"### "===a.substr(r.start-i,i)||(i=3,"###"===a.substr(r.start-i,i))?(t.setSelection(r.start-i,r.end),t.replaceSelection(e),n=r.start-i):r.start>0&&(o=a.substr(r.start-1,1),!!o&&"\n"!=o)?(t.replaceSelection("\n\n### "+e),n=r.start+6):(t.replaceSelection("### "+e),n=r.start+4),t.setSelection(n,n+e.length)}}]},{name:"groupLink",data:[{name:"cmdUrl",title:"URL/Link",hotkey:"Ctrl+L",icon:{glyph:"glyphicon glyphicon-link",fa:"fa fa-link","fa-3":"icon-link",octicons:"octicon octicon-link"},callback:function(e){var n,i,o,r=e.getSelection();e.getContent();n=0===r.length?e.__localize("enter link description here"):r.text,o=prompt(e.__localize("Insert Hyperlink"),"http://");var a=new RegExp("^((http|https)://|(mailto:)|(//))[a-z0-9]","i");if(null!==o&&""!==o&&"http://"!==o&&a.test(o)){var s=t("<div>"+o+"</div>").text();e.replaceSelection("["+n+"]("+s+")"),i=r.start+1,e.setSelection(i,i+n.length)}}},{name:"cmdImage",title:"Image",hotkey:"Ctrl+G",icon:{glyph:"glyphicon glyphicon-picture",fa:"fa fa-picture-o","fa-3":"icon-picture",octicons:"octicon octicon-file-media"},callback:function(e){var n,i,o,r=e.getSelection();e.getContent();n=0===r.length?e.__localize("enter image description here"):r.text,o=prompt(e.__localize("Insert Image Hyperlink"),"http://");var a=new RegExp("^((http|https)://|(//))[a-z0-9]","i");if(null!==o&&""!==o&&"http://"!==o&&a.test(o)){var s=t("<div>"+o+"</div>").text();e.replaceSelection("!["+n+"]("+s+' "'+e.__localize("enter image title here")+'")'),i=r.start+2,e.setNextTab(e.__localize("enter image title here")),e.setSelection(i,i+n.length)}}}]},{name:"groupMisc",data:[{name:"cmdList",hotkey:"Ctrl+U",title:"Unordered List",icon:{glyph:"glyphicon glyphicon-list",fa:"fa fa-list","fa-3":"icon-list-ul",octicons:"octicon octicon-list-unordered"},callback:function(e){var n,i,o=e.getSelection();e.getContent();if(0===o.length)n=e.__localize("list text here"),e.replaceSelection("- "+n),i=o.start+2;else if(o.text.indexOf("\n")<0)n=o.text,e.replaceSelection("- "+n),i=o.start+2;else{var r=[];r=o.text.split("\n"),n=r[0],t.each(r,function(t,e){r[t]="- "+e}),e.replaceSelection("\n\n"+r.join("\n")),i=o.start+4}e.setSelection(i,i+n.length)}},{name:"cmdListO",hotkey:"Ctrl+O",title:"Ordered List",icon:{glyph:"glyphicon glyphicon-th-list",fa:"fa fa-list-ol","fa-3":"icon-list-ol",octicons:"octicon octicon-list-ordered"},callback:function(e){var n,i,o=e.getSelection();e.getContent();if(0===o.length)n=e.__localize("list text here"),e.replaceSelection("1. "+n),i=o.start+3;else if(o.text.indexOf("\n")<0)n=o.text,e.replaceSelection("1. "+n),i=o.start+3;else{var r=[];r=o.text.split("\n"),n=r[0],t.each(r,function(t,e){r[t]="1. "+e}),e.replaceSelection("\n\n"+r.join("\n")),i=o.start+5}e.setSelection(i,i+n.length)}},{name:"cmdCode",hotkey:"Ctrl+K",title:"Code",icon:{glyph:"glyphicon glyphicon-console",fa:"fa fa-code","fa-3":"icon-code",octicons:"octicon octicon-code"},callback:function(t){var e,n,i=t.getSelection(),o=t.getContent();e=0===i.length?t.__localize("code text here"):i.text,"```\n"===o.substr(i.start-4,4)&&"\n```"===o.substr(i.end,4)?(t.setSelection(i.start-4,i.end+4),t.replaceSelection(e),n=i.start-4):"`"===o.substr(i.start-1,1)&&"`"===o.substr(i.end,1)?(t.setSelection(i.start-1,i.end+1),t.replaceSelection(e),n=i.start-1):o.indexOf("\n")>-1?(t.replaceSelection("```\n"+e+"\n```"),n=i.start+4):(t.replaceSelection("`"+e+"`"),n=i.start+1),t.setSelection(n,n+e.length)}},{name:"cmdQuote",hotkey:"Ctrl+Q",title:"Quote",icon:{glyph:"glyphicon glyphicon-comment",fa:"fa fa-quote-left","fa-3":"icon-quote-left",octicons:"octicon octicon-quote"},callback:function(e){var n,i,o=e.getSelection();e.getContent();if(0===o.length)n=e.__localize("quote here"),e.replaceSelection("> "+n),i=o.start+2;else if(o.text.indexOf("\n")<0)n=o.text,e.replaceSelection("> "+n),i=o.start+2;else{var r=[];r=o.text.split("\n"),n=r[0],t.each(r,function(t,e){r[t]="> "+e}),e.replaceSelection("\n\n"+r.join("\n")),i=o.start+4}e.setSelection(i,i+n.length)}}]},{name:"groupUtil",data:[{name:"cmdPreview",toggle:!0,hotkey:"Ctrl+P",title:"Preview",btnText:"Preview",btnClass:"btn btn-primary btn-sm",icon:{glyph:"glyphicon glyphicon-search",fa:"fa fa-search","fa-3":"icon-search",octicons:"octicon octicon-search"},callback:function(t){var e=t.$isPreview;e===!1?t.showPreview():t.hidePreview()}}]}]],additionalButtons:[],reorderButtonGroups:[],hiddenButtons:[],disabledButtons:[],footer:"",fullscreen:{enable:!0,icons:{fullscreenOn:{fa:"fa fa-expand",glyph:"glyphicon glyphicon-fullscreen","fa-3":"icon-resize-full",octicons:"octicon octicon-link-external"},fullscreenOff:{fa:"fa fa-compress",glyph:"glyphicon glyphicon-fullscreen","fa-3":"icon-resize-small",octicons:"octicon octicon-browser"}}},onShow:function(t){},onPreview:function(t){},onSave:function(t){},onBlur:function(t){},onFocus:function(t){},onChange:function(t){},onFullscreen:function(t){},onFullscreenExit:function(t){},onSelect:function(t){}},t.fn.markdown.Constructor=e,t.fn.markdown.noConflict=function(){return t.fn.markdown=n,this};var i=function(t){var e=t;return e.data("markdown")?void e.data("markdown").showEditor():void e.markdown()},o=function(e){var n=t(document.activeElement);t(document).find(".md-editor").each(function(){var e=t(this),i=n.closest(".md-editor")[0]===this,o=e.find("textarea").data("markdown")||e.find('div[data-provider="markdown-preview"]').data("markdown");o&&!i&&o.blur()})};t(document).on("click.markdown.data-api",'[data-provide="markdown-editable"]',function(e){i(t(this)),e.preventDefault()}).on("click focusin",function(t){o(t)}).ready(function(){t('textarea[data-provide="markdown"]').each(function(){i(t(this))})})}),$(document).ready(function(){var t=$(".sky"),e=$(document).height();t.css("height",e-150),toastr.options={positionClass:"toast-top-center"}});var QRCode;!function(){function t(t){this.mode=c.MODE_8BIT_BYTE,this.data=t,this.parsedData=[];for(var e=0,n=this.data.length;n>e;e++){var i=[],o=this.data.charCodeAt(e);o>65536?(i[0]=240|(1835008&o)>>>18,i[1]=128|(258048&o)>>>12,i[2]=128|(4032&o)>>>6,i[3]=128|63&o):o>2048?(i[0]=224|(61440&o)>>>12,i[1]=128|(4032&o)>>>6,i[2]=128|63&o):o>128?(i[0]=192|(1984&o)>>>6,i[1]=128|63&o):i[0]=o,this.parsedData.push(i)}this.parsedData=Array.prototype.concat.apply([],this.parsedData),this.parsedData.length!=this.data.length&&(this.parsedData.unshift(191),this.parsedData.unshift(187),this.parsedData.unshift(239))}function e(t,e){this.typeNumber=t,this.errorCorrectLevel=e,this.modules=null,this.moduleCount=0,this.dataCache=null,this.dataList=[]}function n(t,e){if(void 0==t.length)throw new Error(t.length+"/"+e);for(var n=0;n<t.length&&0==t[n];)n++;this.num=new Array(t.length-n+e);for(var i=0;i<t.length-n;i++)this.num[i]=t[i+n]}function i(t,e){this.totalCount=t,this.dataCount=e}function o(){this.buffer=[],this.length=0}function r(){return"undefined"!=typeof CanvasRenderingContext2D}function a(){var t=!1,e=navigator.userAgent;if(/android/i.test(e)){t=!0;var n=e.toString().match(/android ([0-9]\.[0-9])/i);n&&n[1]&&(t=parseFloat(n[1]))}return t}function s(t,e){for(var n=1,i=l(t),o=0,r=g.length;r>=o;o++){var a=0;switch(e){case h.L:a=g[o][0];break;case h.M:a=g[o][1];break;case h.Q:a=g[o][2];break;case h.H:a=g[o][3]}if(a>=i)break;n++}if(n>g.length)throw new Error("Too long data");return n}function l(t){var e=encodeURI(t).toString().replace(/\%[0-9a-fA-F]{2}/g,"a");return e.length+(e.length!=t?3:0)}t.prototype={getLength:function(t){return this.parsedData.length},write:function(t){for(var e=0,n=this.parsedData.length;n>e;e++)t.put(this.parsedData[e],8)}},e.prototype={addData:function(e){var n=new t(e);this.dataList.push(n),this.dataCache=null},isDark:function(t,e){if(0>t||this.moduleCount<=t||0>e||this.moduleCount<=e)throw new Error(t+","+e);return this.modules[t][e]},getModuleCount:function(){return this.moduleCount},make:function(){this.makeImpl(!1,this.getBestMaskPattern())},makeImpl:function(t,n){this.moduleCount=4*this.typeNumber+17,this.modules=new Array(this.moduleCount);for(var i=0;i<this.moduleCount;i++){this.modules[i]=new Array(this.moduleCount);for(var o=0;o<this.moduleCount;o++)this.modules[i][o]=null}this.setupPositionProbePattern(0,0),this.setupPositionProbePattern(this.moduleCount-7,0),this.setupPositionProbePattern(0,this.moduleCount-7),this.setupPositionAdjustPattern(),this.setupTimingPattern(),this.setupTypeInfo(t,n),this.typeNumber>=7&&this.setupTypeNumber(t),null==this.dataCache&&(this.dataCache=e.createData(this.typeNumber,this.errorCorrectLevel,this.dataList)),this.mapData(this.dataCache,n)},setupPositionProbePattern:function(t,e){for(var n=-1;7>=n;n++)if(!(-1>=t+n||this.moduleCount<=t+n))for(var i=-1;7>=i;i++)-1>=e+i||this.moduleCount<=e+i||(n>=0&&6>=n&&(0==i||6==i)||i>=0&&6>=i&&(0==n||6==n)||n>=2&&4>=n&&i>=2&&4>=i?this.modules[t+n][e+i]=!0:this.modules[t+n][e+i]=!1)},getBestMaskPattern:function(){for(var t=0,e=0,n=0;8>n;n++){this.makeImpl(!0,n);var i=d.getLostPoint(this);(0==n||t>i)&&(t=i,e=n)}return e},createMovieClip:function(t,e,n){var i=t.createEmptyMovieClip(e,n),o=1;this.make();for(var r=0;r<this.modules.length;r++)for(var a=r*o,s=0;s<this.modules[r].length;s++){var l=s*o,c=this.modules[r][s];c&&(i.beginFill(0,100),i.moveTo(l,a),i.lineTo(l+o,a),i.lineTo(l+o,a+o),i.lineTo(l,a+o),i.endFill())}return i},setupTimingPattern:function(){for(var t=8;t<this.moduleCount-8;t++)null==this.modules[t][6]&&(this.modules[t][6]=t%2==0);for(var e=8;e<this.moduleCount-8;e++)null==this.modules[6][e]&&(this.modules[6][e]=e%2==0)},setupPositionAdjustPattern:function(){for(var t=d.getPatternPosition(this.typeNumber),e=0;e<t.length;e++)for(var n=0;n<t.length;n++){var i=t[e],o=t[n];if(null==this.modules[i][o])for(var r=-2;2>=r;r++)for(var a=-2;2>=a;a++)-2==r||2==r||-2==a||2==a||0==r&&0==a?this.modules[i+r][o+a]=!0:this.modules[i+r][o+a]=!1}},setupTypeNumber:function(t){for(var e=d.getBCHTypeNumber(this.typeNumber),n=0;18>n;n++){var i=!t&&1==(e>>n&1);this.modules[Math.floor(n/3)][n%3+this.moduleCount-8-3]=i}for(var n=0;18>n;n++){var i=!t&&1==(e>>n&1);this.modules[n%3+this.moduleCount-8-3][Math.floor(n/3)]=i}},setupTypeInfo:function(t,e){for(var n=this.errorCorrectLevel<<3|e,i=d.getBCHTypeInfo(n),o=0;15>o;o++){var r=!t&&1==(i>>o&1);6>o?this.modules[o][8]=r:8>o?this.modules[o+1][8]=r:this.modules[this.moduleCount-15+o][8]=r}for(var o=0;15>o;o++){var r=!t&&1==(i>>o&1);8>o?this.modules[8][this.moduleCount-o-1]=r:9>o?this.modules[8][15-o-1+1]=r:this.modules[8][15-o-1]=r}this.modules[this.moduleCount-8][8]=!t},mapData:function(t,e){for(var n=-1,i=this.moduleCount-1,o=7,r=0,a=this.moduleCount-1;a>0;a-=2)for(6==a&&a--;;){for(var s=0;2>s;s++)if(null==this.modules[i][a-s]){var l=!1;r<t.length&&(l=1==(t[r]>>>o&1));var c=d.getMask(e,i,a-s);c&&(l=!l),this.modules[i][a-s]=l,o--,-1==o&&(r++,o=7)}if(i+=n,0>i||this.moduleCount<=i){i-=n,n=-n;break}}}},e.PAD0=236,e.PAD1=17,e.createData=function(t,n,r){for(var a=i.getRSBlocks(t,n),s=new o,l=0;l<r.length;l++){var c=r[l];s.put(c.mode,4),s.put(c.getLength(),d.getLengthInBits(c.mode,t)),c.write(s)}for(var h=0,l=0;l<a.length;l++)h+=a[l].dataCount;if(s.getLengthInBits()>8*h)throw new Error("code length overflow. ("+s.getLengthInBits()+">"+8*h+")");for(s.getLengthInBits()+4<=8*h&&s.put(0,4);s.getLengthInBits()%8!=0;)s.putBit(!1);for(;!(s.getLengthInBits()>=8*h)&&(s.put(e.PAD0,8),!(s.getLengthInBits()>=8*h));)s.put(e.PAD1,8);return e.createBytes(s,a)},e.createBytes=function(t,e){for(var i=0,o=0,r=0,a=new Array(e.length),s=new Array(e.length),l=0;l<e.length;l++){var c=e[l].dataCount,h=e[l].totalCount-c;o=Math.max(o,c),r=Math.max(r,h),a[l]=new Array(c);for(var u=0;u<a[l].length;u++)a[l][u]=255&t.buffer[u+i];i+=c;var p=d.getErrorCorrectPolynomial(h),f=new n(a[l],p.getLength()-1),g=f.mod(p);s[l]=new Array(p.getLength()-1);for(var u=0;u<s[l].length;u++){var m=u+g.getLength()-s[l].length;s[l][u]=m>=0?g.get(m):0}}for(var v=0,u=0;u<e.length;u++)v+=e[u].totalCount;for(var w=new Array(v),y=0,u=0;o>u;u++)for(var l=0;l<e.length;l++)u<a[l].length&&(w[y++]=a[l][u]);for(var u=0;r>u;u++)for(var l=0;l<e.length;l++)u<s[l].length&&(w[y++]=s[l][u]);return w};for(var c={MODE_NUMBER:1,MODE_ALPHA_NUM:2,MODE_8BIT_BYTE:4,MODE_KANJI:8},h={L:1,M:0,Q:3,H:2},u={PATTERN000:0,PATTERN001:1,PATTERN010:2,PATTERN011:3,PATTERN100:4,PATTERN101:5,PATTERN110:6,PATTERN111:7},d={PATTERN_POSITION_TABLE:[[],[6,18],[6,22],[6,26],[6,30],[6,34],[6,22,38],[6,24,42],[6,26,46],[6,28,50],[6,30,54],[6,32,58],[6,34,62],[6,26,46,66],[6,26,48,70],[6,26,50,74],[6,30,54,78],[6,30,56,82],[6,30,58,86],[6,34,62,90],[6,28,50,72,94],[6,26,50,74,98],[6,30,54,78,102],[6,28,54,80,106],[6,32,58,84,110],[6,30,58,86,114],[6,34,62,90,118],[6,26,50,74,98,122],[6,30,54,78,102,126],[6,26,52,78,104,130],[6,30,56,82,108,134],[6,34,60,86,112,138],[6,30,58,86,114,142],[6,34,62,90,118,146],[6,30,54,78,102,126,150],[6,24,50,76,102,128,154],[6,28,54,80,106,132,158],[6,32,58,84,110,136,162],[6,26,54,82,110,138,166],[6,30,58,86,114,142,170]],G15:1335,G18:7973,G15_MASK:21522,getBCHTypeInfo:function(t){for(var e=t<<10;d.getBCHDigit(e)-d.getBCHDigit(d.G15)>=0;)e^=d.G15<<d.getBCHDigit(e)-d.getBCHDigit(d.G15);return(t<<10|e)^d.G15_MASK},getBCHTypeNumber:function(t){for(var e=t<<12;d.getBCHDigit(e)-d.getBCHDigit(d.G18)>=0;)e^=d.G18<<d.getBCHDigit(e)-d.getBCHDigit(d.G18);return t<<12|e},getBCHDigit:function(t){for(var e=0;0!=t;)e++,t>>>=1;return e},getPatternPosition:function(t){return d.PATTERN_POSITION_TABLE[t-1]},getMask:function(t,e,n){switch(t){case u.PATTERN000:return(e+n)%2==0;case u.PATTERN001:return e%2==0;case u.PATTERN010:return n%3==0;case u.PATTERN011:return(e+n)%3==0;case u.PATTERN100:return(Math.floor(e/2)+Math.floor(n/3))%2==0;case u.PATTERN101:return e*n%2+e*n%3==0;case u.PATTERN110:return(e*n%2+e*n%3)%2==0;case u.PATTERN111:return(e*n%3+(e+n)%2)%2==0;default:throw new Error("bad maskPattern:"+t)}},getErrorCorrectPolynomial:function(t){for(var e=new n([1],0),i=0;t>i;i++)e=e.multiply(new n([1,p.gexp(i)],0));return e},getLengthInBits:function(t,e){if(e>=1&&10>e)switch(t){case c.MODE_NUMBER:return 10;case c.MODE_ALPHA_NUM:return 9;case c.MODE_8BIT_BYTE:return 8;case c.MODE_KANJI:return 8;default:throw new Error("mode:"+t)}else if(27>e)switch(t){case c.MODE_NUMBER:return 12;case c.MODE_ALPHA_NUM:return 11;case c.MODE_8BIT_BYTE:return 16;case c.MODE_KANJI:return 10;default:throw new Error("mode:"+t)}else{if(!(41>e))throw new Error("type:"+e);switch(t){case c.MODE_NUMBER:return 14;case c.MODE_ALPHA_NUM:return 13;case c.MODE_8BIT_BYTE:return 16;case c.MODE_KANJI:return 12;default:throw new Error("mode:"+t)}}},getLostPoint:function(t){for(var e=t.getModuleCount(),n=0,i=0;e>i;i++)for(var o=0;e>o;o++){for(var r=0,a=t.isDark(i,o),s=-1;1>=s;s++)if(!(0>i+s||i+s>=e))for(var l=-1;1>=l;l++)0>o+l||o+l>=e||(0!=s||0!=l)&&a==t.isDark(i+s,o+l)&&r++;r>5&&(n+=3+r-5)}for(var i=0;e-1>i;i++)for(var o=0;e-1>o;o++){var c=0;t.isDark(i,o)&&c++,t.isDark(i+1,o)&&c++,t.isDark(i,o+1)&&c++,t.isDark(i+1,o+1)&&c++,(0==c||4==c)&&(n+=3)}for(var i=0;e>i;i++)for(var o=0;e-6>o;o++)t.isDark(i,o)&&!t.isDark(i,o+1)&&t.isDark(i,o+2)&&t.isDark(i,o+3)&&t.isDark(i,o+4)&&!t.isDark(i,o+5)&&t.isDark(i,o+6)&&(n+=40);for(var o=0;e>o;o++)for(var i=0;e-6>i;i++)t.isDark(i,o)&&!t.isDark(i+1,o)&&t.isDark(i+2,o)&&t.isDark(i+3,o)&&t.isDark(i+4,o)&&!t.isDark(i+5,o)&&t.isDark(i+6,o)&&(n+=40);for(var h=0,o=0;e>o;o++)for(var i=0;e>i;i++)t.isDark(i,o)&&h++;var u=Math.abs(100*h/e/e-50)/5;return n+=10*u}},p={glog:function(t){if(1>t)throw new Error("glog("+t+")");return p.LOG_TABLE[t]},gexp:function(t){for(;0>t;)t+=255;for(;t>=256;)t-=255;return p.EXP_TABLE[t]},EXP_TABLE:new Array(256),LOG_TABLE:new Array(256)},f=0;8>f;f++)p.EXP_TABLE[f]=1<<f;for(var f=8;256>f;f++)p.EXP_TABLE[f]=p.EXP_TABLE[f-4]^p.EXP_TABLE[f-5]^p.EXP_TABLE[f-6]^p.EXP_TABLE[f-8];for(var f=0;255>f;f++)p.LOG_TABLE[p.EXP_TABLE[f]]=f;n.prototype={get:function(t){return this.num[t]},getLength:function(){return this.num.length},multiply:function(t){for(var e=new Array(this.getLength()+t.getLength()-1),i=0;i<this.getLength();i++)for(var o=0;o<t.getLength();o++)e[i+o]^=p.gexp(p.glog(this.get(i))+p.glog(t.get(o)));return new n(e,0)},mod:function(t){if(this.getLength()-t.getLength()<0)return this;for(var e=p.glog(this.get(0))-p.glog(t.get(0)),i=new Array(this.getLength()),o=0;o<this.getLength();o++)i[o]=this.get(o);for(var o=0;o<t.getLength();o++)i[o]^=p.gexp(p.glog(t.get(o))+e);return new n(i,0).mod(t)}},i.RS_BLOCK_TABLE=[[1,26,19],[1,26,16],[1,26,13],[1,26,9],[1,44,34],[1,44,28],[1,44,22],[1,44,16],[1,70,55],[1,70,44],[2,35,17],[2,35,13],[1,100,80],[2,50,32],[2,50,24],[4,25,9],[1,134,108],[2,67,43],[2,33,15,2,34,16],[2,33,11,2,34,12],[2,86,68],[4,43,27],[4,43,19],[4,43,15],[2,98,78],[4,49,31],[2,32,14,4,33,15],[4,39,13,1,40,14],[2,121,97],[2,60,38,2,61,39],[4,40,18,2,41,19],[4,40,14,2,41,15],[2,146,116],[3,58,36,2,59,37],[4,36,16,4,37,17],[4,36,12,4,37,13],[2,86,68,2,87,69],[4,69,43,1,70,44],[6,43,19,2,44,20],[6,43,15,2,44,16],[4,101,81],[1,80,50,4,81,51],[4,50,22,4,51,23],[3,36,12,8,37,13],[2,116,92,2,117,93],[6,58,36,2,59,37],[4,46,20,6,47,21],[7,42,14,4,43,15],[4,133,107],[8,59,37,1,60,38],[8,44,20,4,45,21],[12,33,11,4,34,12],[3,145,115,1,146,116],[4,64,40,5,65,41],[11,36,16,5,37,17],[11,36,12,5,37,13],[5,109,87,1,110,88],[5,65,41,5,66,42],[5,54,24,7,55,25],[11,36,12],[5,122,98,1,123,99],[7,73,45,3,74,46],[15,43,19,2,44,20],[3,45,15,13,46,16],[1,135,107,5,136,108],[10,74,46,1,75,47],[1,50,22,15,51,23],[2,42,14,17,43,15],[5,150,120,1,151,121],[9,69,43,4,70,44],[17,50,22,1,51,23],[2,42,14,19,43,15],[3,141,113,4,142,114],[3,70,44,11,71,45],[17,47,21,4,48,22],[9,39,13,16,40,14],[3,135,107,5,136,108],[3,67,41,13,68,42],[15,54,24,5,55,25],[15,43,15,10,44,16],[4,144,116,4,145,117],[17,68,42],[17,50,22,6,51,23],[19,46,16,6,47,17],[2,139,111,7,140,112],[17,74,46],[7,54,24,16,55,25],[34,37,13],[4,151,121,5,152,122],[4,75,47,14,76,48],[11,54,24,14,55,25],[16,45,15,14,46,16],[6,147,117,4,148,118],[6,73,45,14,74,46],[11,54,24,16,55,25],[30,46,16,2,47,17],[8,132,106,4,133,107],[8,75,47,13,76,48],[7,54,24,22,55,25],[22,45,15,13,46,16],[10,142,114,2,143,115],[19,74,46,4,75,47],[28,50,22,6,51,23],[33,46,16,4,47,17],[8,152,122,4,153,123],[22,73,45,3,74,46],[8,53,23,26,54,24],[12,45,15,28,46,16],[3,147,117,10,148,118],[3,73,45,23,74,46],[4,54,24,31,55,25],[11,45,15,31,46,16],[7,146,116,7,147,117],[21,73,45,7,74,46],[1,53,23,37,54,24],[19,45,15,26,46,16],[5,145,115,10,146,116],[19,75,47,10,76,48],[15,54,24,25,55,25],[23,45,15,25,46,16],[13,145,115,3,146,116],[2,74,46,29,75,47],[42,54,24,1,55,25],[23,45,15,28,46,16],[17,145,115],[10,74,46,23,75,47],[10,54,24,35,55,25],[19,45,15,35,46,16],[17,145,115,1,146,116],[14,74,46,21,75,47],[29,54,24,19,55,25],[11,45,15,46,46,16],[13,145,115,6,146,116],[14,74,46,23,75,47],[44,54,24,7,55,25],[59,46,16,1,47,17],[12,151,121,7,152,122],[12,75,47,26,76,48],[39,54,24,14,55,25],[22,45,15,41,46,16],[6,151,121,14,152,122],[6,75,47,34,76,48],[46,54,24,10,55,25],[2,45,15,64,46,16],[17,152,122,4,153,123],[29,74,46,14,75,47],[49,54,24,10,55,25],[24,45,15,46,46,16],[4,152,122,18,153,123],[13,74,46,32,75,47],[48,54,24,14,55,25],[42,45,15,32,46,16],[20,147,117,4,148,118],[40,75,47,7,76,48],[43,54,24,22,55,25],[10,45,15,67,46,16],[19,148,118,6,149,119],[18,75,47,31,76,48],[34,54,24,34,55,25],[20,45,15,61,46,16]],i.getRSBlocks=function(t,e){var n=i.getRsBlockTable(t,e);if(void 0==n)throw new Error("bad rs block @ typeNumber:"+t+"/errorCorrectLevel:"+e);for(var o=n.length/3,r=[],a=0;o>a;a++)for(var s=n[3*a+0],l=n[3*a+1],c=n[3*a+2],h=0;s>h;h++)r.push(new i(l,c));return r},i.getRsBlockTable=function(t,e){switch(e){case h.L:return i.RS_BLOCK_TABLE[4*(t-1)+0];case h.M:return i.RS_BLOCK_TABLE[4*(t-1)+1];case h.Q:return i.RS_BLOCK_TABLE[4*(t-1)+2];case h.H:return i.RS_BLOCK_TABLE[4*(t-1)+3];default:return}},o.prototype={get:function(t){var e=Math.floor(t/8);return 1==(this.buffer[e]>>>7-t%8&1)},put:function(t,e){for(var n=0;e>n;n++)this.putBit(1==(t>>>e-n-1&1))},getLengthInBits:function(){return this.length},putBit:function(t){var e=Math.floor(this.length/8);this.buffer.length<=e&&this.buffer.push(0),t&&(this.buffer[e]|=128>>>this.length%8),this.length++}};var g=[[17,14,11,7],[32,26,20,14],[53,42,32,24],[78,62,46,34],[106,84,60,44],[134,106,74,58],[154,122,86,64],[192,152,108,84],[230,180,130,98],[271,213,151,119],[321,251,177,137],[367,287,203,155],[425,331,241,177],[458,362,258,194],[520,412,292,220],[586,450,322,250],[644,504,364,280],[718,560,394,310],[792,624,442,338],[858,666,482,382],[929,711,509,403],[1003,779,565,439],[1091,857,611,461],[1171,911,661,511],[1273,997,715,535],[1367,1059,751,593],[1465,1125,805,625],[1528,1190,868,658],[1628,1264,908,698],[1732,1370,982,742],[1840,1452,1030,790],[1952,1538,1112,842],[2068,1628,1168,898],[2188,1722,1228,958],[2303,1809,1283,983],[2431,1911,1351,1051],[2563,1989,1423,1093],[2699,2099,1499,1139],[2809,2213,1579,1219],[2953,2331,1663,1273]],m=function(){var t=function(t,e){this._el=t,this._htOption=e};return t.prototype.draw=function(t){function e(t,e){var n=document.createElementNS("http://www.w3.org/2000/svg",t);for(var i in e)e.hasOwnProperty(i)&&n.setAttribute(i,e[i]);return n}var n=this._htOption,i=this._el,o=t.getModuleCount();Math.floor(n.width/o),Math.floor(n.height/o),this.clear();var r=e("svg",{viewBox:"0 0 "+String(o)+" "+String(o),width:"100%",height:"100%",fill:n.colorLight});r.setAttributeNS("http://www.w3.org/2000/xmlns/","xmlns:xlink","http://www.w3.org/1999/xlink"),i.appendChild(r),r.appendChild(e("rect",{fill:n.colorLight,width:"100%",height:"100%"})),r.appendChild(e("rect",{fill:n.colorDark,width:"1",height:"1",id:"template"}));for(var a=0;o>a;a++)for(var s=0;o>s;s++)if(t.isDark(a,s)){var l=e("use",{x:String(s),y:String(a)});l.setAttributeNS("http://www.w3.org/1999/xlink","href","#template"),r.appendChild(l)}},t.prototype.clear=function(){for(;this._el.hasChildNodes();)this._el.removeChild(this._el.lastChild)},t}(),v="svg"===document.documentElement.tagName.toLowerCase(),w=v?m:r()?function(){function t(){this._elImage.src=this._elCanvas.toDataURL("image/png"),this._elImage.style.display="block",this._elCanvas.style.display="none"}function e(t,e){var n=this;if(n._fFail=e,n._fSuccess=t,null===n._bSupportDataURI){var i=document.createElement("img"),o=function(){n._bSupportDataURI=!1,n._fFail&&n._fFail.call(n)},r=function(){n._bSupportDataURI=!0,n._fSuccess&&n._fSuccess.call(n)};return i.onabort=o,i.onerror=o,i.onload=r,void(i.src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg==")}n._bSupportDataURI===!0&&n._fSuccess?n._fSuccess.call(n):n._bSupportDataURI===!1&&n._fFail&&n._fFail.call(n)}if(this._android&&this._android<=2.1){var n=1/window.devicePixelRatio,i=CanvasRenderingContext2D.prototype.drawImage;CanvasRenderingContext2D.prototype.drawImage=function(t,e,o,r,a,s,l,c,h){if("nodeName"in t&&/img/i.test(t.nodeName))for(var u=arguments.length-1;u>=1;u--)arguments[u]=arguments[u]*n;else"undefined"==typeof c&&(arguments[1]*=n,arguments[2]*=n,arguments[3]*=n,arguments[4]*=n);i.apply(this,arguments)}}var o=function(t,e){this._bIsPainted=!1,this._android=a(),this._htOption=e,this._elCanvas=document.createElement("canvas"),this._elCanvas.width=e.width,this._elCanvas.height=e.height,t.appendChild(this._elCanvas),this._el=t,this._oContext=this._elCanvas.getContext("2d"),this._bIsPainted=!1,this._elImage=document.createElement("img"),this._elImage.alt="Scan me!",this._elImage.style.display="none",this._el.appendChild(this._elImage),this._bSupportDataURI=null};return o.prototype.draw=function(t){var e=this._elImage,n=this._oContext,i=this._htOption,o=t.getModuleCount(),r=i.width/o,a=i.height/o,s=Math.round(r),l=Math.round(a);e.style.display="none",this.clear();for(var c=0;o>c;c++)for(var h=0;o>h;h++){var u=t.isDark(c,h),d=h*r,p=c*a;n.strokeStyle=u?i.colorDark:i.colorLight,n.lineWidth=1,n.fillStyle=u?i.colorDark:i.colorLight,n.fillRect(d,p,r,a),n.strokeRect(Math.floor(d)+.5,Math.floor(p)+.5,s,l),n.strokeRect(Math.ceil(d)-.5,Math.ceil(p)-.5,s,l)}this._bIsPainted=!0},o.prototype.makeImage=function(){this._bIsPainted&&e.call(this,t)},o.prototype.isPainted=function(){return this._bIsPainted},o.prototype.clear=function(){this._oContext.clearRect(0,0,this._elCanvas.width,this._elCanvas.height),this._bIsPainted=!1},o.prototype.round=function(t){return t?Math.floor(1e3*t)/1e3:t},o}():function(){var t=function(t,e){this._el=t,this._htOption=e};return t.prototype.draw=function(t){for(var e=this._htOption,n=this._el,i=t.getModuleCount(),o=Math.floor(e.width/i),r=Math.floor(e.height/i),a=['<table style="border:0;border-collapse:collapse;">'],s=0;i>s;s++){a.push("<tr>");for(var l=0;i>l;l++)a.push('<td style="border:0;border-collapse:collapse;padding:0;margin:0;width:'+o+"px;height:"+r+"px;background-color:"+(t.isDark(s,l)?e.colorDark:e.colorLight)+';"></td>');a.push("</tr>")}a.push("</table>"),n.innerHTML=a.join("");var c=n.childNodes[0],h=(e.width-c.offsetWidth)/2,u=(e.height-c.offsetHeight)/2;h>0&&u>0&&(c.style.margin=u+"px "+h+"px")},t.prototype.clear=function(){this._el.innerHTML=""},t}();QRCode=function(t,e){if(this._htOption={width:256,height:256,typeNumber:4,colorDark:"#000000",colorLight:"#ffffff",correctLevel:h.H},"string"==typeof e&&(e={text:e}),e)for(var n in e)this._htOption[n]=e[n];"string"==typeof t&&(t=document.getElementById(t)),this._htOption.useSVG&&(w=m),this._android=a(),this._el=t,this._oQRCode=null,this._oDrawing=new w(this._el,this._htOption),this._htOption.text&&this.makeCode(this._htOption.text)},QRCode.prototype.makeCode=function(t){this._oQRCode=new e(s(t,this._htOption.correctLevel),this._htOption.correctLevel),this._oQRCode.addData(t),this._oQRCode.make(),this._el.title=t,this._oDrawing.draw(this._oQRCode),this.makeImage()},QRCode.prototype.makeImage=function(){"function"==typeof this._oDrawing.makeImage&&(!this._android||this._android>=3)&&this._oDrawing.makeImage()},QRCode.prototype.clear=function(){this._oDrawing.clear()},QRCode.CorrectLevel=h}(),function(t,e,n){function i(t,e){var n=f({},E,e||{},g(t));h(t,"share-component social-share"),o(t,n),r(t,n),t.initialized=!0}function o(t,e){var n=a(e),i="prepend"==e.mode;v(i?n.reverse():n,function(n){var o=s(n,e),r=e.initialized?d(t,".icon-"+n):p('<a class="social-share-icon icon-'+n+'" target="_blank"></a>');return r.length?(r[0].href=o,void(e.initialized||(i?t.insertBefore(r[0],t.firstChild):t.appendChild(r[0])))):!0})}function r(t,e){var n=d(t,"icon-wechat","a");if(0===n.length)return!1;var i=p('<div class="wechat-qrcode"><h4>'+e.wechatQrcodeTitle+'</h4><div class="qrcode"></div><div class="help">'+e.wechatQrcodeHelper+"</div></div>"),o=d(i[0],"qrcode","div");n[0].appendChild(i[0]),new QRCode(o[0],{text:e.url,width:100,height:100})}function a(t){t.mobileSites.length||(t.mobileSites=t.sites);var e=(_?t.mobileSites:t.sites).slice(0),n=t.disabled;return"string"==typeof e&&(e=e.split(/\s*,\s*/)),"string"==typeof n&&(n=n.split(/\s*,\s*/)),C&&n.push("wechat"),n.length&&v(n,function(t){e.splice(m(t,e),1)}),e}function s(t,e){return e.summary=e.description,$[t].replace(/\{\{(\w)(\w*)\}\}/g,function(n,i,o){var r=t+i+o.toLowerCase();return o=(i+o).toLowerCase(),encodeURIComponent(e[r]||e[o]||"")})}function l(n){return(e.querySelectorAll||t.jQuery||t.Zepto||c).call(e,n)}function c(t){var n=[];return v(t.split(/\s*,\s*/),function(i){var o=i.match(/([#.])(\w+)/);if(null===o)throw Error("Supports only simple single #ID or .CLASS selector.");if(o[1]){var r=e.getElementById(o[2]);r&&n.push(r)}n=n.concat(d(t))}),n}function h(t,e){if(e&&"string"==typeof e){var n=(t.className+" "+e).split(/\s+/),i=" ";v(n,function(t){i.indexOf(" "+t+" ")<0&&(i+=t+" ")}),t.className=i.slice(1,-1)}}function u(t){return(e.getElementsByName(t)[0]||0).content}function d(t,e,n){if(t.getElementsByClassName)return t.getElementsByClassName(e);var i=[],o=t.getElementsByTagName(n||"*");return e=" "+e+" ",v(o,function(t){(" "+(t.className||"")+" ").indexOf(e)>=0&&i.push(t)}),i}function p(t){var n=e.createElement("div");return n.innerHTML=t,n.childNodes}function f(){var t=arguments;if(b)return b.apply(null,t);var e={};return v(t,function(t){v(t,function(t,n){e[n]=t})}),t[0]=e}function g(t){if(t.dataset)return t.dataset;var e={};return t.hasAttributes()?(v(t.attributes,function(t){var n=t.name;return 0!==n.indexOf("data-")?!0:(n=n.replace(/^data-/i,"").replace(/-(\w)/g,function(t,e){return e.toUpperCase()}),void(e[n]=t.value))}),e):{}}function m(t,e,n){var i;if(e){if(y)return y.call(e,t,n);for(i=e.length,n=n?0>n?Math.max(0,i+n):n:0;i>n;n++)if(n in e&&e[n]===t)return n}return-1}function v(t,e){var i=t.length;if(i===n){for(var o in t)if(t.hasOwnProperty(o)&&e.call(t[o],t[o],o)===!1)break}else for(var r=0;i>r&&e.call(t[r],t[r],r)!==!1;r++);}function w(n){var i="addEventListener",o=e[i]?"":"on";~e.readyState.indexOf("m")?n():"load DOMContentLoaded readystatechange".replace(/\w+/g,function(r,a){(a?e:t)[o?"attachEvent":i](o+r,function(){n&&(6>a||~e.readyState.indexOf("m"))&&(n(),n=0)},!1)})}var y=Array.prototype.indexOf,b=Object.assign,C=/MicroMessenger/i.test(navigator.userAgent),_=e.documentElement.clientWidth<=768,k=(e.images[0]||0).src||"",T=u("site")||u("Site")||e.title,x=u("title")||u("Title")||e.title,S=u("description")||u("Description")||"",E={url:location.href,origin:location.origin,source:T,title:x,description:S,image:k,weiboKey:"",wechatQrcodeTitle:"微信扫一扫：分享",wechatQrcodeHelper:"<p>微信里点“发现”，扫一下</p><p>二维码便可将本文分享至朋友圈。</p>",sites:["weibo","qq","wechat","tencent","douban","qzone","linkedin","diandian","facebook","twitter","google"],mobileSites:[],disabled:[],initialized:!1},$={qzone:"http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url={{URL}}&title={{TITLE}}&desc={{DESCRIPTION}}&summary={{SUMMARY}}&site={{SOURCE}}",qq:"http://connect.qq.com/widget/shareqq/index.html?url={{URL}}&title={{TITLE}}&source={{SOURCE}}&desc={{DESCRIPTION}}",tencent:"http://share.v.t.qq.com/index.php?c=share&a=index&title={{TITLE}}&url={{URL}}&pic={{IMAGE}}",weibo:"http://service.weibo.com/share/share.php?url={{URL}}&title={{TITLE}}&pic={{IMAGE}}&appkey={{WEIBOKEY}}",wechat:"javascript:",douban:"http://shuo.douban.com/!service/share?href={{URL}}&name={{TITLE}}&text={{DESCRIPTION}}&image={{IMAGE}}&starid=0&aid=0&style=11",diandian:"http://www.diandian.com/share?lo={{URL}}&ti={{TITLE}}&type=link",linkedin:"http://www.linkedin.com/shareArticle?mini=true&ro=true&title={{TITLE}}&url={{URL}}&summary={{SUMMARY}}&source={{SOURCE}}&armin=armin",facebook:"https://www.facebook.com/sharer/sharer.php?u={{URL}}",twitter:"https://twitter.com/intent/tweet?text={{TITLE}}&url={{URL}}&via={{ORIGIN}}",google:"https://plus.google.com/share?url={{URL}}"};t.socialShare=function(t,e){t="string"==typeof t?l(t):t,t.length===n&&(t=[t]),v(t,function(t){t.initialized||i(t,e)})},w(function(){socialShare(".social-share, .share-component")})}(window,document);
>>>>>>> 2ecb23c5969a61286bd2195059a753bf40cf4df3
