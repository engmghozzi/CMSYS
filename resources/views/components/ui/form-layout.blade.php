<div class="min-h-screen">
	<div class="max-w-4xl mx-auto">
		<div class="mb-6">
			<div class="flex items-center justify-between">
				<div>
					<h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $title ?? '' }}</h1>
					@if (!empty($description))
						<p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
					@elseif (!empty($subtitle))
						<p class="mt-1 text-sm text-gray-600">{{ $subtitle }}</p>
					@endif
				</div>
				@if (!empty($backUrl))
					<a href="{{ $backUrl }}"
					   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
						<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
						</svg>
						{{ $backLabel ?? __('Back') }}
					</a>
				@endif
			</div>
		</div>

		@if ($errors->any())
			<div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
				<div class="text-sm text-red-700">
					<ul class="list-disc pl-5 space-y-1">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			</div>
		@endif

		<div class="bg-white rounded-xl shadow-sm border border-gray-200">
			<div class="p-4 sm:p-6">
				{{ $slot }}
			</div>
		</div>
	</div>
</div>

 