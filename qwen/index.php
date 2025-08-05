<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Search Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="container mx-auto p-6 max-w-lg">
    <h1 class="text-2xl font-bold mb-6 text-center text-blue-700">Search Client</h1>

    <form action="search.php" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Client Name</label>
            <input type="text" name="ClientName" required
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Client Code</label>
            <input type="text" name="ClientCode" required
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition duration-200">
            Search
        </button>
    </form>
</div>

</body>
</html>