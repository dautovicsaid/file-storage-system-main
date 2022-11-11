@extends('layouts.user')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="my-3 text-center">File upload system - Dashboard</h2>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-md-6 offset-md-3 mt-3">
                @if(!$recently_shared_files->isEmpty())
                    <h5>Files recently shared with me</h5>
                @endif
                @foreach ($recently_shared_files as $file)
                    <div class="p-3" style="border: 1px solid #eee">
                        <div class="file-item-select-bg bg-primary"></div>
                        <div @class(
                        ['file-item-icon far text-secondary',
                         'fa-folder' => $file->is_folder,
                         'fa-file-excel' => $file->extension == 'xlsx',
                         'fa-file-word' => $file->extension == 'docx',
                         'fa-regular fa-image' => in_array($file->extension ,['jpg','jpeg','png']),
                         'fa-file' => !$file->is_folder && ! in_array($file->extension ,['xlsx', 'docx', 'jpg', 'jpeg', 'png'])
                         ])></div>
                        @if($file->is_folder)
                            <a href=" {{route('sharedFiles.getAllSharedFiles',['folder_id'=>$file->id])}}">
                                {{$file->name}}
                            </a>
                        @else
                            {{$file->name . "." . $file->extension}}
                        @endif
                        @if(!$file->is_folder)
                            <a class="link-success ms-2 size" href="{{route('files.download',['file'=>$file->id])}}">Download</a>
                        @endif
                        <br/>
                        <small>(shared by {{$file->shared_by}} at {{$file->shared_at}})</small>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row mt-5">
            <div @class(['offset-md-2' => !$files_by_extension->isEmpty(),
                         'offset-md-4' => $files_by_extension->isEmpty(),
                         'col-md-4 mt-3'])>
                <canvas id="storageUsageChart" width="250" height="250"></canvas>
            </div>
            @if(!$files_by_extension->isEmpty())
                <div class="col-md-4 mt-3">
                    <canvas id="filesByExtensionChart" width="250" height="250"></canvas>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
            integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.1.0/chartjs-plugin-datalabels.min.js"
        integrity="sha512-Tfw6etYMUhL4RTki37niav99C6OHwMDB2iBT5S5piyHO+ltK2YX8Hjy9TXxhE1Gm/TmAV0uaykSpnHKFIAif/A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="module">
        showStorageUsageChart();
        showFilesByExtensionChart();

        function showStorageUsageChart() {
            const ctx = document.getElementById('storageUsageChart').getContext('2d');
            const user = @json(auth()->user());

            const storageLimit = user.storage_limit;
            const usedStorage = user.storage_used;
            const freeStorage = storageLimit - usedStorage;

            new Chart(ctx, {
                plugins: [ChartDataLabels],
                type: 'pie',
                data: {
                    labels: ['Used', 'Free'],
                    datasets: [{
                        data: [usedStorage, freeStorage],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(75, 192, 192, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        datalabels: {
                            formatter: (value) => {
                                const percentage = value * 100 / storageLimit;
                                if (percentage < 10) {
                                    return null;
                                }
                                return percentage.toFixed(2) + "%";
                            }
                        },
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Storage usage'
                        }
                    }
                },

            });
        }

        function showFilesByExtensionChart() {
            const ctx = document.getElementById('filesByExtensionChart').getContext('2d');
            const filesByExtension = @json($files_by_extension);

            if (filesByExtension.length == 0) {
                return;
            }

            const totalCount = filesByExtension
                .map(fileByExtension => fileByExtension.count)
                .reduce((sum, count) => sum + count, 0);

            const otherCountThreshold = totalCount * (5 / 100);

            const dataset = filesByExtension.filter(fileByExtension => fileByExtension.count >= otherCountThreshold);

            const otherCount = filesByExtension
                .filter(fileByExtension => fileByExtension.count < otherCountThreshold)
                .map(fileByExtension => fileByExtension.count)
                .reduce((sum, count) => sum + count, 0);

            if (otherCount > 0) {
                dataset.push({
                    extension: 'other',
                    count: otherCount
                });
            }

            const labels = dataset.map(dataEntry => dataEntry.extension);
            const data = dataset.map(dataEntry => dataEntry.count);

            new Chart(ctx, {
                plugins: [ChartDataLabels],
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        datalabels: {
                            formatter: (value) => {
                                const percentage = value * 100 / totalCount;
                                if (percentage < 10) {
                                    return null;
                                }
                                return percentage.toFixed(2) + "%";
                            },
                            color: '#fff',
                        },
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'File count by extension'
                        }
                    }
                },

            });
        }

        // $(document).ready(function () {
        //
        // });
    </script>
@endsection
