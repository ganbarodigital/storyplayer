---
layout: v2/modules-browser
title: expectsBrowser()
prev: '<a href="../../modules/browser/fromBrowser.html">Prev: fromBrowser()</a>'
next: '<a href="../../modules/browser/usingBrowser.html">Next: usingBrowser()</a>'
updated_for_v2: true
---

# expectsBrowser()

_expectsBrowser()_ allows you to test the currently loaded HTML page and make sure that it contains what you expect it to.  Use these tests to prove that your story can continue.

The source code for these actions can be found in the class `Prose\ExpectsBrowser`.

## Behaviour And Return Codes

Every action is a test of some kind.

* If the test succeeds, the action returns control to your code, and does not return a value.
* If the test fails, the action throws an exception.  _Do not catch exceptions thrown by these actions_.  Let them go through to Storyplayer, which will use the information to work out whether your story as a whole passes or fails.

Write your story as if every test must pass.

## doesntHave()

Use `expectsBrowser()->doesntHave()` to ensure that the currently loaded HTML page _doesn't_ contain a specified DOM element or elements.  This is the direct opposite of _[expectsBrowser()->has()](#has)_.

{% highlight php startinline %}
expectsBrowser()->doesntHave()->linkWithText("Login");
expectsBrowser()->doesntHave()->fieldsWithClass("invoice");
{% endhighlight %}

See _[has()](#has)_ below for a longer discussion.

## has()

Use `expectsBrowser()->has()` to ensure that the currently loaded HTML page contains a specified DOM element or elements.

{% highlight php startinline %}
expectsBrowser()->has()->formWithId("registration");
expectsBrowser()->has()->linkWithText("Login");
expectsBrowser()->has()->fieldsWithClass("invoice");
{% endhighlight %}

Some web-based applications can show different content on the same URL, depending on whether the end-user is logged into the app or not.  You often see this on website home pages.  By checking for the presence (using _has()_) or absence (using _doesntHave()_) of elements on the page, you can work out what state the app currently is in.

## hasTitle()

Use `expectsBrowser()->hasTitle()` to ensure that the currently loaded HTML page has the _&lt;title&gt;_ that you expect.

{% highlight php startinline %}
expectsBrowser()->hasTitle("Dashboard");
{% endhighlight %}

This is very commonly used after submitting a form, clicking on a link, or telling the browser to go to a specific URL, to make sure that the browser is looking at the page that you expect.

## hasTitles()

Use `expectsBrowser()->hasTitles()` to ensure that the currently loaded HTML page's _&lt;title&gt;_ matches one of the titles in your list.

{% highlight php startinline %}
expectsBrowser()->hasTitles(array("Home", "Dashboard"));
{% endhighlight %}

Sometimes, when you click on a link or submit a form, your web-based application may not take you to the same page every time.  For example, in a payment wizard, your app might have a step called "Personal Details" which only appears when some of the details are missing.  In these situations, this action makes it very easy to make sure that the browser has (after redirects) ended up on a page that you expect - and not one that you don't.

## isBlank()

Use `expectsBrowser()->isBlank()` to ensure that an input field on the currently loaded HTML page is blank (i.e. has an empty 'value' attribute).

{% highlight php startinline %}
expectsBrowser()->firstFieldWithId("blank_value")->isBlank();
{% endhighlight %}

Note that the [browser search term](searching-the-dom.html) comes in the middle of this statement, rather than at the end.

## isNotBlank()

Use `expectsBrowser()->isNotBlank()` to ensure that an input field on the currently loaded HTML page is not blank (i.e. has an 'value' attribute that is not empty).

{% highlight php startinline %}
expectsBrowser()->firstFieldWithId("username")->isNotBlank();
{% endhighlight %}

Note that the [browser search term](searching-the-dom.html) comes in the middle of this statement, rather than at the end.

## isChecked()

Use `expectsBrowser()->isChecked()` to ensure that a checkbox or radio button on the currently loaded HTML page has been selected.

{% highlight php startinline %}
expectsBrowser()->firstFieldWithLabel("I have read the terms and conditions")->isChecked();
{% endhighlight %}

Note that the [browser search term](searching-the-dom.html) comes in the middle of this statement, rather than at the end.

## isNotChecked()

Use `expectsBrowser()->isNotChecked()` to ensure that a checkbox or radio button on the currently loaded HTML page has not been selected.

{% highlight php startinline %}
expectsBrowser()->firstFieldWithLabel("I have read the terms and conditions")->isNotChecked();
{% endhighlight %}

Note that the [browser search term](searching-the-dom.html) comes in the middle of this statement, rather than at the end.