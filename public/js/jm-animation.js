var Animation = function({ offset } = { offset: 10 }) {
  var _elements;

  // Sets the top, bottom, and sides of the canvas
  var windowTop = offset * window.innerHeight / 100;
  var windowBottom = window.innerHeight - windowTop;
  var windowLeft = 0;
  var windowRight = window.innerWidth;

  function start(element) {
    // Set custom attributes
    element.style.animationDelay = element.dataset.animationDelay;
    element.style.animationDuration = element.dataset.animationDuration;
    // Start the animation by entering the animation class
    element.classList.add(element.dataset.animation);
    // Set the element as animated
    element.dataset.animated = "true";
  }

  function isElementOnScreen(element) {
    // Gets the element's boundingbox
    var elementRect = element.getBoundingClientRect();
    var elementTop =
      elementRect.top + parseInt(element.dataset.animationOffset) ||
      elementRect.top;
    var elementBottom =
      elementRect.bottom - parseInt(element.dataset.animationOffset) ||
      elementRect.bottom;
    var elementLeft = elementRect.left;
    var elementRight = elementRect.right;

    // Checks if the element is on the screen
    return (
      elementTop <= windowBottom &&
      elementBottom >= windowTop &&
      elementLeft <= windowRight &&
      elementRight >= windowLeft
    );
  }

  // Loops through the array of elements, checks if the element is on screen, and starts animation
  function checkElementsOnScreen(els = _elements) {
    for (var i = 0, len = els.length; i < len; i++) {
      // Skips to the next loop if the element is already animated
      if (els[i].dataset.animated) continue;

      isElementOnScreen(els[i]) && start(els[i]);
    }
  }

  // Updates the list of elements to be animated
  function update() {
    _elements = document.querySelectorAll(
      "[data-animation]:not([data-animated])"
    );
    checkElementsOnScreen(_elements);
  }

  // Start the events
  window.addEventListener("load", update, false);
  window.addEventListener("scroll", () => checkElementsOnScreen(_elements), { passive: true });
  window.addEventListener("resize", () => checkElementsOnScreen(_elements), false);

  // Returns public functions
  return {
    start,
    isElementOnScreen,
    update
  };
};

// Initialize
var options = {
  offset: 20 //percentage of window
};
var animation = new Animation(options);