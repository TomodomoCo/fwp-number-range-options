# FacetWP Number Range Buttons

This is a custom facet for [FacetWP](http://www.facetwp.com/), a faceted search plugin for WordPress.

It extends/improves FacetWP's number range facet by allowing pre-defined numeric ranges. This allows your content creators to enter a specific number in the source data, but simplifies the user experience by providing a set of smart default options.

## Example

You might have a site that consists of an archive of historical events, with a year for each event.

```
Han dynasty founded: 25
Vesuvius erupts: 79
Norman Conquest: 1066
Columbus reaches America: 1492
Suez Canal opened: 1879
Berlin Wall falls: 1989
```

With this data, you could create a facet that allows users to search by historical era, instead of needing to enter a specific range of years.

```
Classical antiquity: 0—499
Middle ages: 500—1399
Early modern period: 1400—1849
Machine age: 1850—1945
Cold war: 1946—1995
```

In the facet, these range choies are formatted as `Label | Range value`, with new choices on new lines, e.g.:

```
Classical antiquity: 0—499 | 0-499
Middle ages: 500—1399 | 500-1399
Early modern period: 1400—1849 | 1400-1849
Machine age: 1850—1945 | 1850-1945
Cold war: 1946–1995 | 1846-1995
```

Note that the range separator in the value **must be a hyphen**, _not_ the [typographically correct en dash](https://en.wikipedia.org/wiki/Dash#En_dash). You may use the en dash in the label (as I have done in the above example), but it will not be parsed correctly if used in the range value.

## License

**Copyright (c) 2016 [Van Patten Media Inc.](https://www.vanpattenmedia.com/) All rights reserved.**

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

*   Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
*   Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
*   Neither the name of the organization nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
