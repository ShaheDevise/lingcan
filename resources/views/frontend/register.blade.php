@section('title')
Register
@endsection
<x-header />

    <!-- Contact Section Begin -->
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 mx-auto">
                    <div class="section-title">
                        <h2>Create an account</h2>
                    </div>
                    <div class="contact__form">
                        <form action="{{ route("registerUser") }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text" name="fullname" placeholder="Full Name" required>
                                </div>

                                <div class="col-lg-6">
                                    <input type="email" name="email" placeholder="Email" required>
                                </div>

                                <div class="col-lg-12">
                                    <input type="file" name="picture" required>
                                </div>

                                <div class="col-lg-12">
                                    <input type="password" name="password" placeholder="Password" required>
                                </div>

                                <div class="col-lg-12">
                                    <button type="submit" class="site-btn">Register</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->

    <x-footer />
