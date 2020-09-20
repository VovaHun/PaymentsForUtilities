package dev.akat.smartmeter.ui

import android.os.Bundle
import android.os.Handler
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import androidx.navigation.fragment.findNavController
import dev.akat.smartmeter.MeterApplication
import dev.akat.smartmeter.R
import dev.akat.smartmeter.di.AppComponent
import dev.akat.smartmeter.repository.MeterRepository
import javax.inject.Inject

class SplashFragment : Fragment() {

    @Inject
    lateinit var meterRepository: MeterRepository

    private val appComponent: AppComponent by lazy(mode = LazyThreadSafetyMode.NONE) {
        (activity?.application as MeterApplication).appComponent
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        appComponent.inject(this)
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? =
        inflater.inflate(R.layout.fragment_splash, container, false)

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        Handler().postDelayed({
            meterRepository.isLoggedIn()
            if (meterRepository.isLoggedIn())
                findNavController().navigate(R.id.action_splashFragment_to_accountListFragment)
            else
                findNavController().navigate(R.id.action_splashFragment_to_login)
        }, 1000)
    }
}