<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 text-center border border-transparent rounded-full font-semibold text-xs tracking-widest disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
