
  widgetbackdrop = document.getElementById("bloknot-widget-backdrop");
  widgetcontainer = document.getElementById("bloknotapp-widget-container");
  widgetshow = 0;

  function widgetShow()
  {
    if (widgetshow == 1)
    {
      widgetshow = 0;
      widgetbackdrop.classList.add("hide");
      widgetbackdrop.classList.remove("show");
      widgetcontainer.classList.add("hide");
      widgetcontainer.classList.remove("show");
      document.body.style.overflow = "visible";
    }
    else if (widgetshow == 0)
    {
      widgetshow = 1;
      widgetbackdrop.classList.remove("hide");
      widgetbackdrop.classList.add("show");
      widgetcontainer.classList.remove("hide");
      widgetcontainer.classList.add("show");
      document.body.style.overflow = "hidden";
    }
  };
