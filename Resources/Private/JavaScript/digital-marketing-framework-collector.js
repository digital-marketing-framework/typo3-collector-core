/**
 * usage:
 *
 * window.digitalMarketingFramework.collect(data => {
 *   console.log('collected data:');
 *   console.log(data);
 * });
 *
 * window.digitalMarketingFramework.collect('myMapName', data => {
 *   console.log('collected data for myMapName:');
 *   console.log(data);
 * });
 */

(function(container) {
  let requests = {};

  // function getUrl(map) {
  //   let url = '/?type=1490631647';
  //   if (map) {
  //     url += '&tx_dmfcollectorcore_ajaxservice[map]=' + encodeURIComponent(map);
  //   }
  //   return url;
  // }

  function getUrl(map) {
    let url = '/digital-marketing-framework';
    if (map) {
      url += '/' + encodeURIComponent(map);
    }
    url += '/ajax-user-data.json';
    return url;
  }

  function finishRequest(request, result) {
    request.loaded = true;
    request.result = result;
    request.callbacks.forEach(callback => {
      callback(result);
    });
  }

  function startRequest(map, request) {
    const url = getUrl(map);
    container.fetch(url)
      .then(response => response.json())
      .then(data => {
        finishRequest(request, data);
      })
      .catch(error => {
        container.console.error('Error:', error);
        finishRequest(request, false);
      });
  }

  function collect(map, callback) {
    let request = requests[map];
    if (!request) {
      request = {
        loaded: false,
        callbacks: [],
        result: false
      };
      if (callback) {
        request.callbacks.push(callback);
      }
      requests[map] = request;
      startRequest(map, request);
    } else if (callback) {
      if (request.loaded) {
        setTimeout(() => { callback(request.result); }, 0);
      } else {
        request.callbacks.push(callback);
      }
    }
  }

  function reset(map) {
    if (map) {
      delete(requests[map]);
    } else {
      requests = {};
    }
  }

  container.digitalMarketingFramework = container.digitalMarketingFramework || {};

  container.digitalMarketingFramework.collect = function() {
    const map = arguments.length === 2 ? arguments[0] : '';
    const callback = arguments.length === 2 ? arguments[1] : arguments[0];
    collect(map, callback);
  };

  container.digitalMarketingFramework.resetCollector = function(map) {
    reset(map);
  };

})(window);
