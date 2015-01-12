﻿using Microsoft.AspNet.Mvc.Rendering;
using Kendo.Mvc.UI.Fluent;

namespace Kendo.Mvc.UI
{    public static class HtmlHelperExtension
    {
        public static WidgetFactory Kendo(this HtmlHelper helper)
        {
            return new WidgetFactory(helper);
        }

        public static WidgetFactory<TModel> Kendo<TModel>(this HtmlHelper<TModel> helper)
        {
            return new WidgetFactory<TModel>(helper);
        }
    }
}