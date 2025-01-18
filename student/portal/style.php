<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f0f0f0;
    }

    .container {
        max-width: 500px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        justify-content: space-around;
        cursor: pointer;
        transition: transform 0.3s ease-in-out;
    }

    .profile {
        text-align: start;
        margin-bottom: 20px;
    }

    .profile img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .profile h2 {
        color: #333;
        margin-bottom: 10px;
        
        text-align: center;
        
    }

    .profile p {
        color: #000;
        margin-top: 20px;
        margin-bottom: 20px;
        font-size: larger;
    }

    .profile .edit-profile {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .profile .edit-profile:hover {
        background-color: #0056b3;
    }

    @media (max-width: 768px) {
        .container {
            max-width: 90%;
        }
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 5px;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
</style>