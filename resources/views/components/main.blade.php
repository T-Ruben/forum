<!-- Changes:
Took from main: border rounded-t-lg border-gray-700/75
Took from section: bg-gray-500/75  -->

<main class="text-gray-300">
    <div class="flex flex-row w-full h-full gap-2">
        <section class="p-4 flex flex-col justify-start w-full rounded-t-md h-auto">

            {{ $slot }}

        </section>

    </div>

</main>
