package dev.akat.smartmeter.ui.queryList

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.fragment.app.Fragment
import androidx.fragment.app.viewModels
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProvider
import androidx.navigation.fragment.findNavController
import androidx.recyclerview.widget.DividerItemDecoration
import dev.akat.smartmeter.MeterApplication
import dev.akat.smartmeter.R
import dev.akat.smartmeter.di.AppComponent
import kotlinx.android.synthetic.main.fragment_account_list.*
import kotlinx.android.synthetic.main.fragment_query_list.*
import javax.inject.Inject

class QueryListFragment : Fragment() {

    @Inject
    lateinit var viewModelFactory: ViewModelProvider.Factory

    private val appComponent: AppComponent by lazy(mode = LazyThreadSafetyMode.NONE) {
        (activity?.application as MeterApplication).appComponent
    }

    private val viewModel: QueryListViewModel by viewModels { viewModelFactory }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        appComponent.inject(this)
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? =
        inflater.inflate(R.layout.fragment_query_list, container, false)

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        initRecyclerView()
        subscribeLoading()
        subscribeErrors()

        query_list_swipe.setOnRefreshListener {
            viewModel.loadQueries()
            query_list_swipe.isRefreshing = false
        }

        query_list_fab.setOnClickListener {
            findNavController().navigate(R.id.action_queryListFragment_to_companyListFragment)
        }
    }

    private fun initRecyclerView() {
        val queryListAdapter = QueryListAdapter()
        query_list_rv.adapter = queryListAdapter
        query_list_rv.addItemDecoration(
            DividerItemDecoration(
                context,
                DividerItemDecoration.VERTICAL
            )
        )

        viewModel.queryList.observe(viewLifecycleOwner, Observer { queryList ->
            queryListAdapter.submitList(queryList)
        })
    }

    private fun subscribeLoading() {
        viewModel.isLoading.observe(viewLifecycleOwner, Observer {
            it.getContentIfNotHandled()?.let { isLoading ->
                if (isLoading) showNoItemLoading()
                else showDataView()
            }
        })
    }

    private fun subscribeErrors() {
        viewModel.errorMessage.observe(viewLifecycleOwner, Observer {
            it.getContentIfNotHandled()?.let { message ->
                if (message.isNotEmpty()) showToast(message)
            }
        })
    }

    private fun showToast(message: String) {
        Toast.makeText(context, message, Toast.LENGTH_LONG).show()
    }

    private fun showDataView() {
        query_list_rv.visibility = View.VISIBLE
        queries_no_item_loading.visibility = View.GONE
    }

    private fun showNoItemLoading() {
        query_list_rv.visibility = View.INVISIBLE
        queries_no_item_loading.visibility = View.VISIBLE
    }
}