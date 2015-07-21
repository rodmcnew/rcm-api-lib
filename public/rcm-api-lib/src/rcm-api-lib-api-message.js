/**
 * Class ApiMessage
 */
angular.module('rcmApiLib')
    .factory(
    'rcmApiLibApiMessage',
    function ($http, $log) {

        /**
         * Class ApiMessage
         */
        return function () {
            var self = this;
            self.type = 'rcmApiLib';
            self.source = 'client';
            self.code = 'error';
            self.value = 'An unknown error occured while making request';
            self.primary = null;
            self.params = [];
            self.key = [];
            // Client only property
            self.level = 'warning';

            /**
             * buildKey
             */
            self.buildKey = function () {
                self.key = self.type + '.' + self.source + '.' + self.code;
            };

            /**
             * init
             */
            self.init = function () {
                self.buildKey();
            };

            self.init();
        };
    }
);