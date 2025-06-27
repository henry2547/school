 <!DOCTYPE html>
 <html lang="">

 <head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Lecturer</title>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
   
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   
   <style>
     .loading-overlay {
       position: fixed;
       top: 0;
       left: 0;
       width: 100%;
       height: 100%;
       background-color: rgba(255, 255, 255, 0.5);
       display: none;
       justify-content: center;
       align-items: center;
       z-index: 9999;
       /* Ensure a high z-index */
     }

     .loading-icon {
       width: 50px;
       height: 50px;
       border: 4px solid #f3f3f3;
       border-top: 4px solid #3498db;
       border-radius: 50%;
       animation: spin 1s linear infinite;
       display: inline-block;
       /* Make the loading icon visible */
     }

     @keyframes spin {
       0% {
         transform: rotate(0deg);
       }

       100% {
         transform: rotate(360deg);
       }
     }
   </style>


 </head>

 <body>