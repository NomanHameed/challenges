<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
      <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                display: flex;
                margin-left: 25%;
            }

            .position-ref {
                position: relative;
            }

            .code {
                border-right: 2px solid;
                font-size: 18px;
                padding: 10px;
                text-align: center;
            }

            .message {
                
                font-size: 18px;
                text-align: center;
            }

            .redirect {
                font-size: 18px;
                text-align: center;
            }

            .center { 
                  
                  position: relative;
                  /*border: 3px solid green; */
                }

                .center .code-center {
                  margin: 0;
                  position: absolute;
                  top: 50%;
                  left: 50%;
                  -ms-transform: translate(-50%, -50%);
                  transform: translate(-50%, -50%);
                }
            
        </style>
    </head>
    <body>
        <div class="center full-height">
            <div class="code-center">
                <div class="flex-center">
                    <div class="code">@yield('code')</div>
                    <div class="message" style="padding: 10px;">@yield('message')</div>
                </div>
                
                <div class="redirect" style="padding: 10px;">
                    <form action="@yield('redirect')">
                        <!-- <a href="@yield('redirect')">Return to Home</a> -->
                        <button type="submit" class="theme-btn green-btn" id="add-challenge-log-form-button">Return to Home</button>
                    </form>
                    
                </div>
            </div>
        </div>

        <!-- <div class="flex-center position-ref full-height">
            <div >
                <div class="code">
                    @yield('code')
                </div>

                <div class="message" style="padding: 10px;">
                    @yield('message')
                </div>
            </div>
            
            <div class="redirect" style="padding: 10px;">
                <form action="@yield('redirect')">
                    
                    <button type="submit" class="theme-btn green-btn" id="add-challenge-log-form-button">Return to Home</button>
                </form>
                
            </div>
                      
        </div> -->
        <!-- <div class="flex-center position-ref full-height">
            
        </div> -->
    </body>
</html>
