define(['jquery'], function($) {
    'use strict';

    return function(navigationBreadcrumb) {
        $.widget('mage.breadcrumbs', $.mage.breadcrumbs, {
            _getCategoryCrumb: function (menuItem) {
                return {
                    'name': 'category',
                    'label': menuItem.find('span').not('.ui-menu-icon').first().text(),
                    'link': menuItem.attr('href'),
                    'title': ''
                };
            },
        });
        return $.mage.breadcrumbs;
    }
});
